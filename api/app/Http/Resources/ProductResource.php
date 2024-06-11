<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->formattedPrice(),
            'description' => $this->description,
            'quantity' => $this->quantity,
            'photo' => $this->photo_url,
            'category' => $this->category,  // Include the category
        ];
    }
}
