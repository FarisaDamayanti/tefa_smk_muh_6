<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends ApiRequest
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
            'attendances' => 'required|array|min:1',
            'attendances.*.id' => 'required|string',
            'attendances.*.type' => 'required|in:' . RoleEnum::STUDENT->value . ',' . RoleEnum::TEACHER->value,
            'attendances.*.time' => 'required|date_format:H:i:s',
            'date' => 'required|date',
        ];
    }
}
