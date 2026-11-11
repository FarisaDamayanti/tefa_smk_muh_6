<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\ExamQuestionOptionInterface;
use App\Models\ExamQuestionOption;
use Illuminate\Support\Facades\DB;

class ExamQuestionOptionRepository extends BaseRepository implements ExamQuestionOptionInterface
{
    public function __construct(ExamQuestionOption $ExamQuestionOption)
    {
        $this->model = $ExamQuestionOption;
    }

    public function get(): mixed
    {
        return $this->model->query()->get();
    }

    public function store(array $data): mixed
    {
        return $this->model->query()->create($data);
    }

    public function show(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id);
    }

    public function update(mixed $id, array $data): mixed
    {
        return $this->model->query()->findOrFail($id)->update($data);
    }

    public function delete(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id)->delete();
    }

    /**
     * query bulk insert
     *
     * @param array $data
     * @return integer
     */
    public function insert(array $data): int
    {
        return $this->model->query()->insert($data);
    }

    /**
     * query bulk update
     *
     * @param string $examQuestionId
     * @param array $options
     * @return int
     */
    public function bulkUpdateByExamQuestion(string $examQuestionId, array $options): int
    {
        $labels = collect($options)->pluck('option_label')->toArray();

        $caseOptionText = "CASE option_label ";
        $caseIsCorrect  = "CASE option_label ";

        foreach ($options as $option) {
            $label = $option['option_label'];
            $text  = addslashes($option['option_text']);
            $isCorrect = $option['is_correct'] ? 1 : 0;

            $caseOptionText .= "WHEN '{$label}' THEN '{$text}' ";
            $caseIsCorrect  .= "WHEN '{$label}' THEN {$isCorrect} ";
        }

        $caseOptionText .= "END";
        $caseIsCorrect  .= "END";

        return $this->model->query()
            ->where('exam_question_id', $examQuestionId)
            ->whereIn('option_label', $labels)
            ->update([
                'option_text' => DB::raw($caseOptionText),
                'is_correct'  => DB::raw($caseIsCorrect),
            ]);
        }

    /**
     * query delete where in
     *
     * @param array $data
     * @return int|mixed
     */
    public function deleteWhereIn(array $data): int
    {
        return $this->model->query()
            ->whereIn('id', $data)
            ->delete();
    }
}