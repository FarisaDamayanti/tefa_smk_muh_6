<?php

namespace App\Http\Requests\Teacher;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class GradingExamQuestionEssayRequest extends ApiRequest
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
        $maxScore = $this->route('studentQuestionAnswer')->examQuestion->score;

        return [
            'score' => 'required|numeric|min:0|max:' . $maxScore,
        ];
    }

    public function messages(): array
    {
        return [
            'score.required' => 'Nilai wajib diisi.',
            'score.numeric' => 'Nilai harus berupa angka.',
            'score.min' => 'Nilai tidak boleh minus.',
            'score.max' => 'Nilai maksimal :max.'
        ];
    }
}
