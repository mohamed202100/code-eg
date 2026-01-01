<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'price',
        'status',
        'image',
        'stock'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected $casts = [
        'price' => 'decimal:2',
    ];

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
}
