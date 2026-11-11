<?php

namespace App\Http\Requests\Student;

use App\Enums\AnswerOptionEnum;
use App\Enums\ExamQuestionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'questions' => 'array|nullable',
            'questions.*.id' => 'nullable|exists:exam_questions,id',
            'questions.*.type' => 'nullable|in:' . ExamQuestionTypeEnum::join(ExamQuestionTypeEnum::SINGLE_CHOICE, ExamQuestionTypeEnum::MULTIPLE_CHOICE, ExamQuestionTypeEnum::MATCHING, ExamQuestionTypeEnum::ESSAY),
            'questions.*.answers.*.option_id' => 'nullable|exists:exam_question_options,id',
            'questions.*.answers.*.left_item_id' => 'nullable|exists:exam_question_matchings,id',
            'questions.*.answers.*.right_item_id' => 'nullable|exists:exam_question_matchings,id',
            'questions.*.answers_text' => 'nullable|string|max:4294967295',
        ];
    }
}
