<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'total_price',
        'payment_method',
        'order_status',
    ];

    protected $dates = ['deleted_at'];

    protected $attributes = [
        'payment_method' => 'cashier', // Default value for payment_method
        'order_status' => 'pending', // Default value for order_status
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
