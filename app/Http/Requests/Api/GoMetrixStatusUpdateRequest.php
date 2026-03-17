<?php

namespace App\Http\Requests\Api;

class GoMetrixStatusUpdateRequest extends ApiFormRequest
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
            'source_system' => 'required|string|in:gometrix',
            'source_order_id' => 'required|integer|exists:orders,id',
            'delivery_order_id' => 'nullable|integer',
            'reference' => 'nullable|string|max:255',
            'status' => 'required|string|max:50',
            'payment_status' => 'nullable|string|max:50',
            'xendit_invoice_id' => 'nullable|string|max:255',
            'xendit_invoice_url' => 'nullable|string|max:1000',
            'event_at' => 'nullable|date',
            'courier' => 'nullable|array',
            'courier.id' => 'nullable|integer',
            'courier.name' => 'nullable|string|max:255',
            'courier.phone' => 'nullable|string|max:255',
            'location' => 'nullable|array',
            'location.lat' => 'nullable|numeric|between:-90,90',
            'location.lng' => 'nullable|numeric|between:-180,180',
        ];
    }
}
