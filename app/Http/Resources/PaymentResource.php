<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'subscription_id' => $this->subscription_id,
            'amount' => $this->amount,
            'bill_ref_no' => $this->bill_ref_no,
            'transaction_id' => $this->transaction_id,
            'type' => $this->type,
            'transaction_time' => $this->transaction_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
