<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Size;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'photo', 'category' // Include 'category' field
    ];

    public function formattedPrice()
    {
        return '$' . number_format($this->price, 2);
    }

    // Accessor for the photo URL
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? url($this->photo) : null;
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(Size::class);
    }
}
