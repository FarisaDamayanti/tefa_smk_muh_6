<?php

namespace App\Imports;

use App\Contracts\Repositories\ExamQuestionMatchingAnswerRepository;
use App\Contracts\Repositories\ExamQuestionMatchingRepository;
use App\Contracts\Repositories\ExamQuestionOptionRepository;
use App\Contracts\Repositories\ExamQuestionRepository;
use App\Enums\AnswerOptionEnum;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterImport;
use App\Enums\ExamQuestionTypeEnum as EQTypeEnum;
use App\Enums\GroupExamQuestionMatchingEnum;
use App\Models\QuestionBank;
use App\Services\Teacher\ExamQuestionService;
use Closure;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Row;
use Symfony\Component\HttpKernel\Exception\HttpException;
use ValueError;

class ExamQuestionImport implements WithEvents, ToModel, WithHeadingRow
{
    public $errors = [];
    public $success = 0;
    public $failed = 0;
    private $index = 1;

    /**
     * Undocumented variable
     *
     * @var 'multiple_choice'|'matching'|'essay'
     */
    private string $currentType;
    private ExamQuestionRepository $examQuestion;
    private ExamQuestionMatchingRepository $examQuestionMatching;
    private ExamQuestionMatchingAnswerRepository $examQuestionMatchingAnswer;
    private ExamQuestionOptionRepository $examQuestionOption;
    private ExamQuestionService $examQuestionService;

    /**
     * Handler of each type of question
     *
     * @var array{
     *  multiple_choice: 'handleExamMultiple',
     *  matching: 'handleExamMatching',
     *  essay: 'handleExamEssay'
     * }
     */
    private array $handler =  [
        'multiple_choice' => 'handleExamMultiple',
        'matching'        => 'handleExamMatching',
        'essay'           => 'handleExamEssay',
    ];

    /**
     * Data that to be inserted to database
     *
     * @var array{
     *  questions: array{
     *      id: string,
     *      employee_id: string,
     *      question_bank_id: string,
     *      question: string,
     *      score: int,
     *      type: 'matching'|'essay'|'single_choice'|'multiple_choice',
     *      created_at: string,
     *      updated_at: string
     * }[],
     *  matchings: array{
     *      id: string,
     *      item_text: string,
     *      exam_question_id: string,
     *      group: 'left'|'right',
     *      created_at: string,
     *      updated_at: string
     * }[],
     *  matching_answers: array{
     *      id: string,
     *      left_item_id: string,
     *      right_item_id: string,
     *      created_at: string,
     *      updated_at: string
     * }[],
     *  options: array{
     *      id: string,
     *      exam_question_id: string,
     *      option_text: string,
     *      option_label: string,
     *      is_correct: bool,
     *      created_at: string,
     *      updated_at: string
     * }[],
     * }
     */
    public array $insert = [
        'questions' => [],
        'matchings' => [],
        'matching_answers' => [],
        'options' => []
    ];

    private $mapMultiple = [
        'jawaban_a'      => [AnswerOptionEnum::OPTION_A->value, 'a'],
        'jawaban_b'      => [AnswerOptionEnum::OPTION_B->value, 'b'],
        'jawaban_c'      => [AnswerOptionEnum::OPTION_C->value, 'c'],
        'jawaban_d'      => [AnswerOptionEnum::OPTION_D->value, 'd'],
        'jawaban_e'      => [AnswerOptionEnum::OPTION_E->value, 'e'],
    ];

    /**
     * Create new import instance
     *
     * @param QuestionBank $questionBank
     * @param string $employeeId
     * @param array{
     *  examQuestion: ExamQuestionRepository,
     *  examQuestionMatching: ExamQuestionMatchingRepository,
     *  examQuestionMatchingAnswer: ExamQuestionMatchingAnswerRepository,
     *  examQuestionOption: ExamQuestionOptionRepository,
     *  examQuestionService: ExamQuestionService,
     * } $dependency
     */
    public function __construct(
        private QuestionBank $questionBank,
        private string $employeeId,
        private array $dependency = [
            'examQuestion' => null,
            'examQuestionMatching' => null,
            'examQuestionMatchingAnswer' => null,
            'examQuestionOption' => null,
        ]
    ) {
        foreach ($dependency as $dep => $instance) {
            if ($instance == null) throw new Exception("Dependency yang disebutkan tidak boleh null.");
            $this->{$dep} = $instance;
        }
    }

    private function titlenify(string $column)
    {
        return Str::title(str_replace('_', ' ', $column));
    }

    public function model(array $row)
    {
        $type = $this->currentType;
        return $this->{$this->handler[$type]}($row);
    }

    private function handleExamEssay(array $row)
    {
        $isValid = true;
        $now = now();
        $insert = [
            'id' => (string) Str::uuid(),
            'employee_id' => $this->employeeId,
            'question_bank_id' => $this->questionBank->id,
            'question' => '',
            'score' => 0,
            'type' => EQTypeEnum::ESSAY->value,
            'created_at' => $now,
            'updated_at' => $now
        ];
        foreach ($row as $column => $value) {
            if (is_null($value)) {
                $this->errors['essay']['row_' . $this->index][] = "Kolom {$this->titlenify($column)} kosong.";
                $isValid = false;
                continue;
            }

            if ($column == 'pertanyaan') {
                $insert['question'] = $value;
            }

            if ($column == 'bobot') {
                $insert['score'] = (int) $value;
            }
        }
        if ($isValid) {
            $this->success++;
            $this->insert['questions'][] = $insert;
        } else {
            $this->failed++;
        }
        $this->index++;
    }

    private function handleExamMultiple(array $row)
    {
        $now = now();
        $isValid = true;
        $question = [
            'id' => (string) Str::uuid(),
            'employee_id' => $this->employeeId,
            'question_bank_id' => $this->questionBank->id,
            'question' => null,
            'score' => null,
            'type' => null,
            'created_at' => $now,
            'updated_at' => $now
        ];
        $options = [];

        foreach ($row as $column => $data) {
            if (is_null($data)) {
                $isValid = false;
                $this->errors['multiple_choice']['row_' . $this->index][] = "Kolom {$this->titlenify($column)} kosong.";
            }

            if ($column == 'pertanyaan') {
                $question['question'] = $data;
                continue;
            }

            if ($column == 'kunci_jawaban') {
                $exploded = explode(',', str_replace(' ', '', $data));
                $question['type'] = (count($exploded) > 1) ? EQTypeEnum::MULTIPLE_CHOICE->value : EQTypeEnum::SINGLE_CHOICE->value;

                if(empty($exploded)){
                    $isValid = false;
                    continue;
                }
                foreach ($exploded as $item) {
                    $lowered = strtolower($item);
                    $options[$lowered]['is_correct'] = true;
                }
                continue;
            }

            if (isset($this->mapMultiple[$column])) {
                if (is_null($data)) {
                    $this->errors['multiple_choice']['row_' . $this->index][] = "Kolom {$this->titlenify($column)} kosong.";
                    continue;
                }
                $res = $this->mapMultiple[$column];
                $options[$res[1]] = [
                    'id' => (string) Str::uuid(),
                    'exam_question_id' => $question['id'],
                    'option_text' => $data,
                    'option_label' => $res[0],
                    'is_correct' => false,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
        }

        if (count($options) < 5) {
            $isValid = false;
        }

        if ($isValid) {
            $this->success++;
            $this->insert['questions'][] = $question;
            array_push($this->insert['options'], ...array_values($options));
        } else {
            $this->failed++;
        }
        $this->index++;
    }

    private function handleExamMatching(array $row)
    {
        $now = now();
        $question = [
            'id' => (string) Str::uuid(),
            'employee_id' => $this->employeeId,
            'question_bank_id' => $this->questionBank->id,
            'question' => null,
            'score' => null,
            'type' => EQTypeEnum::MATCHING->value,
            'created_at' => $now,
            'updated_at' => $now
        ];

        $isValid = true;
        $right = null;
        $left = null;
        $temp = [];

        foreach ($row as $column => $value) {
            if ($column == 'pertanyaan') {
                $question['question'] = $value;
                if (is_null($value)) {
                    $this->errors['matching']['row_' . $this->index][] = "Kolom {$this->titlenify($column)} kosong.";
                    $isValid = false;
                }
                continue;
            }

            if ($column == 'bobot') {
                $question['score'] = $value;
                if (is_null($value)) {
                    $this->errors['matching']['row_' . $this->index][] = "Kolom {$this->titlenify($column)} kosong.";
                    $isValid = false;
                }
                continue;
            }

            if (str_starts_with($column, 'pernyataan_')) {
                if (is_null($value)) {
                    $this->errors['matching']['row_' . $this->index][] = "Kolom {$this->titlenify($column)} kosong.";
                    continue;
                }
                $this->insert['matchings'][] = $left = [
                    'id' => (string) Str::uuid(),
                    'item_text' => $value,
                    'group' => GroupExamQuestionMatchingEnum::LEFT->value,
                    'exam_question_id' => $question['id'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                continue;
            }

            if (str_starts_with($column, 'pasangan_')) {
                if (is_null($value)) {
                    if ($left) array_pop($this->insert['matchings']);
                    $this->errors['matching']['row_' . $this->index][] = "Kolom {$this->titlenify($column)} kosong.";
                    continue;
                }

                if (!$left) continue;

                $this->insert['matchings'][] = $right = [
                    'id' => (string) Str::uuid(),
                    'item_text' => $value,
                    'group' => GroupExamQuestionMatchingEnum::RIGHT->value,
                    'exam_question_id' => $question['id'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }

            if ($left && $right) {
                $this->insert['matching_answers'][] = $temp[] = [
                    'id' => (string) Str::uuid(),
                    'left_item_id' => $left['id'],
                    'right_item_id' => $right['id'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                $left = $right = null;
            }
        }

        if(!empty($temp) || $isValid){
            $this->success++;
            $this->insert['questions'][] = $question;
        } else {
            $this->failed++;
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $this->currentType = $event->getSheet()->getTitle();
            },
            AfterSheet::class => function (AfterSheet $event) {
                $this->index = 1;
            },
            AfterImport::class => function (AfterImport $event) {
                $this->examQuestionService->canAdd($this->questionBank, count($this->insert['questions']));
                $this->examQuestion->insert($this->insert['questions']);
                $this->examQuestionMatching->insert($this->insert['matchings']);
                $this->examQuestionMatchingAnswer->insert($this->insert['matching_answers']);
                $this->examQuestionOption->insert($this->insert['options']);
            }
        ];
    }
}
