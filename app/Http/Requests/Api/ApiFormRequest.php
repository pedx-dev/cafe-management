<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class ApiFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator): void
    {
        $errors = collect($validator->errors()->messages())
            ->flatMap(fn (array $messages, string $field) => collect($messages)->map(fn (string $message) => [
                'field' => $field,
                'message' => $message,
            ]))
            ->values()
            ->all();

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'data' => (object) [],
            'errors' => $errors,
        ], 422));
    }
}
