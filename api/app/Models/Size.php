<?php

// In the Size model (App\Models\Size.php)
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = [
        'product_id', 'size', 'quantity'
    ];

    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }
}
