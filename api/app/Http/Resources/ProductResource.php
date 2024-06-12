<?php

// ProductResource.php
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
            'category' => $this->category,
            'photo' => $this->photo_url,
            'sizes' => $this->sizes->map(function ($size) {
                return [
                    'size' => $size->size,
                    'quantity' => $size->quantity // Include quantity for each size
                ];
            }),
        ];
    }
}

