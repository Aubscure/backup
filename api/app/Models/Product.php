<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'quantity', 'price', 'photo', 'category' // Include 'category' field
    ];

    public function formattedPrice()
    {
        return '$' . number_format($this->price, 2);
    }

    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    // Accessor for the photo URL
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? url($this->photo) : null;
    }
}
