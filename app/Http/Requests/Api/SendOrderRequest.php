<?php

namespace App\Http\Requests\Api;

use Illuminate\Validation\Validator;

class SendOrderRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'order_id' => 'nullable|integer|exists:orders,id',
            'order_code' => 'nullable|string|exists:orders,order_code',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->filled('order_id') && ! $this->filled('order_code')) {
                $validator->errors()->add('order_id', 'At least one identifier is required.');
            }
        });
    }
}
