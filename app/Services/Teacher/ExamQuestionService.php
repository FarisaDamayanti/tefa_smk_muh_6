<?php

namespace App\Services\Teacher;

use App\Contracts\Repositories\ExamQuestionMatchingAnswerRepository;
use App\Contracts\Repositories\ExamQuestionMatchingRepository;
use App\Contracts\Repositories\ExamQuestionOptionRepository;
use App\Contracts\Repositories\ExamQuestionRepository;
use App\Contracts\Repositories\LevelClassRepository;
use App\Contracts\Repositories\SchoolYearRepository;
use App\Contracts\Repositories\SubjectRepository;
use App\Enums\ExamQuestionTypeEnum;
use App\Enums\SemesterEnum;
use App\Http\Requests\Teacher\ExamQuestionCopyRequest;
use App\Http\Requests\Teacher\ExamQuestionRequest;
use App\Models\ExamQuestion;
use App\Models\QuestionBank;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExamQuestionService
{
    public function __construct
    (
        private ExamQuestionRepository $examQuestion,
        private ExamQuestionOptionRepository $examQuestionOption,
        private ExamQuestionMatchingRepository $examQuestionMatching,
        private ExamQuestionMatchingAnswerRepository $examQuestionMatchingAnswer,
        private SubjectRepository $subject,
        private LevelClassRepository $levelClass,
        private SchoolYearRepository $schoolYear,
    ){}

    /**
     * mapping data overview question bank
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        $subjects = $this->subject->getAndFilterById($request);
        $levelClasses = $this->levelClass->getAndFilterById($request);
        $schoolYears = $this->schoolYear->getAndFilterById($request);

        if($request->semester) {
            $semesters = [$request->semester];
        } else {
            $semesters = SemesterEnum::cases();
        }


        $data = [];

        foreach($subjects as $subject) {
            foreach($levelClasses as $levelClass) {
                foreach($schoolYears as $schoolYear) {
                    foreach($semesters as $semester) {
                        $semesterValue = $semester?->value ?? $semester;

                        $data[] = [
                            'subject_id' => $subject->id,
                            'level_class_id' => $levelClass->id,
                            'school_year_id' => $schoolYear->id,
                            'semester' => $semesterValue,
                            'school_year' => $schoolYear->school_year,
                            'level_class_name' => $levelClass->name,
                            'subject_name' => $subject->name,
                            'exam_question_count' => $this->examQuestion->getCountWithFilter($subject->id, $levelClass->id, $schoolYear->id, $semesterValue),
                        ];
                    }
                }
            }
        }

        return $data;
    }

    public function handleUpdateOrCreate(ExamQuestionRequest $request, QuestionBank $questionBank, ?string $id = null)
    {
        $data = $request->validated();
        $employee_id = auth_employee()->id;
        $totalFilled = $this->examQuestion->getTotalSubmittedQuestions($questionBank->id, $employee_id);
        $isUpdate = $request->isUpdate();

        // is bulk menentukan apakah request itu banyak pertanyaa atau hanya satu
        if ($request->isBulk()) {
            if(!$isUpdate && ($totalFilled + count($data)) >= $questionBank->question_limit){
                throw new HttpException(Response::HTTP_FORBIDDEN,"Data yang akan diisi akan melebihi jumlah pertanyaan yang ditentukan.");
            } else if($isUpdate && $this->examQuestion->hasUsed(array_column($data['questions'], 'id'))){
                throw new HttpException(Response::HTTP_FORBIDDEN, "Diantara sola yang akan diupdate, terdapat yang sudah digunakan.");
            }

            // jika banyak, tambahkan question_bank_id dan employee_id pada setiap data
            $data = $this->attachQuestionBankAndEmployee($data['questions'], $questionBank->id);
            /**
             * Upsert akan mengupdate data ketika di dalam data terdapat id yang sama atau tidak,
             * Jika tidak upsert akan mengcreate data tersebut, jika id tidak diisi, maka dia akan menginsert data tersebut, jika ada,
             * upsert akan mengupdate data tersebut (Bulk update perlu id di setiap element)
             */
            $this->examQuestion->upsert($data, ['id'], [
                'question',
                'option_a',
                'option_b',
                'option_c',
                'option_d',
                'option_e',
                'answer'
            ]);
        } else {
            // attach question_bank_id dan employee_id
            $data['question_bank_id'] = $questionBank->id;
            $data['employee_id'] = $employee_id;

            // Jika method adalah put maka dia adalah update, jika post makan dianggap create
            if ($isUpdate) {
                $this->handleSingleUpdate($id, $data);
            } else {
                if ($totalFilled >= $questionBank->question_limit) {
                    throw new HttpException(Response::HTTP_FORBIDDEN, "Jumlah pertanyaan sudah cukup.",);
                }
                $this->examQuestion->store($data);
            }
        }
    }

    /**
     * Handle update, throw if error
     *
     * @param string $id
     * @param array $data
     * @return mixed
     */
    private function handleSingleUpdate(string $id, array $data)
    {
        if(! $this->examQuestion->hasUsed($id)) {
            return $this->examQuestion->update($id, $data);
        }

        throw new HttpException(Response::HTTP_FORBIDDEN, "Soal tersebut sudah digunakan pada ujian.");
    }

    /**
     * Attach question_bank_id adn employee_id to the data
     *
     * @param array $data
     * @param string $questionBank
     * @return array
     */
    private function attachQuestionBankAndEmployee(array $data, string $questionBank)
    {
        $employee_id = auth_employee()->id;

        foreach($data as &$item){
            $item['question_bank_id'] = $questionBank;
            $item['employee_id'] = $employee_id;
        }

        return $data;
    }

    /**
     * handle logic copy question
     *
     * @param ExamQuestionCopyRequest $request
     * @param QuestionBank $questionBank
     * @return void
     */
    public function handleExamQuestionCopy(ExamQuestionCopyRequest $request, QuestionBank $questionBank): void
    {
        $ids = $request->validated()['questions'];

        $this->canAdd($questionBank, count($ids));

        $examQuestions = $this->examQuestion->whereInGetWith($ids);

        if($examQuestions->first()->question_bank_id == $questionBank->id){
            throw new HttpException(Response::HTTP_FORBIDDEN, "Tidak bisa mensalin soal ke bank soal yang sama.");
        }

        $newExamQuestions = [];
        $newExamOptions = [];
        $newExamMatchings = [];
        $newExamMatchingAnswers = [];
        $now = now();

        foreach ($examQuestions as $examQuestion) {
            $newExamQuestionId = (string) Str::uuid();

            $newExamQuestions[] = [
                'id' => $newExamQuestionId,
                'employee_id' => $examQuestion->employee_id,
                'question_bank_id' => $questionBank->id,
                'type' => $examQuestion->type,
                'question' => $examQuestion->question,
                'score' => $examQuestion->score,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($examQuestion->type == ExamQuestionTypeEnum::SINGLE_CHOICE->value || $examQuestion->type == ExamQuestionTypeEnum::MULTIPLE_CHOICE->value) {
                foreach ($examQuestion->sortExamQuestionOptions as $option) {
                    $newExamOptions[] = [
                        'id' => (string) Str::uuid(),
                        'exam_question_id' => $newExamQuestionId,
                        'option_label' => $option->option_label,
                        'option_text' => $option->option_text,
                        'is_correct' => $option->is_correct,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            } else if ($examQuestion->type == ExamQuestionTypeEnum::MATCHING->value) {
                $leftIdMap = [];
                $rightIdMap = [];

                foreach ($examQuestion->leftExamQuestionMatchings as $leftMatching) {
                    $newLeftId = (string) Str::uuid();
                    $leftIdMap[$leftMatching->id] = $newLeftId;

                    $newExamMatchings[] = [
                        'id' => $newLeftId,
                        'exam_question_id' => $newExamQuestionId,
                        'item_text' => $leftMatching->item_text,
                        'group' => $leftMatching->group,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                foreach ($examQuestion->rightExamQuestionMatchings as $rightMatching) {
                    $newRightId = (string) Str::uuid();
                    $rightIdMap[$rightMatching->id] = $newRightId;

                    $newExamMatchings[] = [
                        'id' => $newRightId,
                        'exam_question_id' => $newExamQuestionId,
                        'item_text' => $rightMatching->item_text,
                        'group' => $rightMatching->group,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                foreach ($examQuestion->leftExamQuestionMatchings as $leftMatching) {
                    if (!$leftMatching->leftItemAnswer) {
                        continue;
                    }

                    $oldLeftId  = $leftMatching->id;
                    $oldRightId = $leftMatching->leftItemAnswer->right_item_id;

                    $newExamMatchingAnswers[] = [
                        'id' => (string) Str::uuid(),
                        'left_item_id' => $leftIdMap[$oldLeftId],
                        'right_item_id' => $rightIdMap[$oldRightId],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        $this->examQuestion->insert($newExamQuestions);

        if (!empty($newExamOptions)) {
            $this->examQuestionOption->insert($newExamOptions);
        }

        if (!empty($newExamMatchings)) {
            $this->examQuestionMatching->insert($newExamMatchings);
        }

        if (!empty($newExamMatchingAnswers)) {
            $this->examQuestionMatchingAnswer->insert($newExamMatchingAnswers);
        }
    }


    /**
     * Check if Teacher can add question
     *
     * @param QuestionBank $questionBank
     * @param integer $addNum
     * @return void
     */
    public function canAdd(QuestionBank $questionBank, int $addNum): void
    {
        $totalFilled = $this->examQuestion->getTotalSubmittedQuestions($questionBank->id, auth_employee()->id);

        if(($addNum + $totalFilled) > $questionBank->question_limit){
            throw new HttpException(Response::HTTP_FORBIDDEN, "Jumlah soal yang akan ditambah melebihi batas");
        }
    }
}
