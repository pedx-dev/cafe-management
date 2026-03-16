<?php

namespace App\Http\Requests\Api;

class FastTrackStatusUpdateRequest extends ApiFormRequest
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
            'source_system' => 'required|string|in:fasttrack',
            'source_order_id' => 'required|integer|exists:orders,id',
            'delivery_order_id' => 'nullable',
            'reference' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
            'event_at' => 'nullable|date',
            'courier' => 'nullable|array',
            'courier.id' => 'nullable',
            'courier.name' => 'nullable|string|max:255',
            'courier.phone' => 'nullable|string|max:255',
        ];
    }
}
