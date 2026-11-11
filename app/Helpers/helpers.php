<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

if (!function_exists('auth_school')) {
    function auth_school()
    {
        return auth()->user()->school;
    }
}

if (!function_exists('auth_student')) {
    function auth_student()
    {
        return auth()->user()->student;
    }
}
if (!function_exists('auth_employee')) {
    function auth_employee()
    {
        return auth()->user()->employee;
    }
}
if (!function_exists('auth_user')) {
    function auth_user()
    {
        return auth()->user();
    }
}

if (!function_exists('message')) {
    function message(string $key, string|Throwable|null $throwableOrString = null): string
    {
        $base = trans($key);

        if ($throwableOrString === null) {
            return $base;
        }

        if (is_string($throwableOrString)) {
            Log::error($throwableOrString);
            return $base . '. ' . $throwableOrString;
        }

        Log::error($throwableOrString->getMessage());

        if ($throwableOrString instanceof ModelNotFoundException) {
            $model = class_basename($throwableOrString->getModel());
            return $base . '. ' . trans('alert.model_not_found') . ': ' . $model;
        }

        return $base . '. ' . $throwableOrString->getMessage();
    }
}
