<?php

namespace App\Http\Requests;

use App\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends FormRequest
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
            'current_password' => [
                'required',
                function ($attr, $value, $fail) {
                    if (! Hash::check($value, auth()->user()?->password)) {
                        $fail('Kata sandi saat ini tidak sesuai.');
                    }
                },
            ],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'password_confirmation' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',

            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',

            'password_confirmation.required' => 'Konfirmasi kata sandi wajib diisi.'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->routeIs('api.*')) {
            $errors = $validator->errors()->messages();
            return ResponseHelper::error("Form validation errors", ResponseCode::HTTP_UNPROCESSABLE_ENTITY, $errors);
        }

        $exception = $validator->getException();

        throw (new $exception($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());

    }
}
