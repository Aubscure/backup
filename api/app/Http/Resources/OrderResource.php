<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer_first_name' => $this->customer->first_name,
            'customer_last_name' => $this->customer->last_name,
            'customer_id_number' => $this->customer->id_number,
            'total_price' => $this->total_price,
            'payment_method' => $this->payment_method ?? 'cashier',
            'order_status' => $this->order_status ?? 'pending',
            'order_details' => OrderDetailResource::collection($this->orderDetails),
        ];
    }
}
