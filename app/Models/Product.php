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

    /**
     * Get all images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order')->orderBy('is_primary', 'desc');
    }

    /**
     * Get the primary image for the product.
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true)
            ->orWhere(function($query) {
                $query->where('product_id', $this->id)->orderBy('order')->limit(1);
            });
    }
    
    /**
     * Get the main image path (primary, first, or fallback).
     */
    public function getMainImagePathAttribute()
    {
        $primaryImage = $this->images()->where('is_primary', true)->first();
        if ($primaryImage) {
            return $primaryImage->image_path;
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->image_path;
        }
        
        return $this->image;
    }

    /**
     * Get the main image (primary or first image, or fallback to old image field).
     * This is a computed attribute that works with eager loaded images.
     */
    public function getMainImageAttribute()
    {
        // If images are already loaded, use them
        if ($this->relationLoaded('images')) {
            $primaryImage = $this->images->where('is_primary', true)->first();
            if ($primaryImage) {
                return $primaryImage->image_path;
            }
            
            $firstImage = $this->images->first();
            if ($firstImage) {
                return $firstImage->image_path;
            }
        } else {
            // If not loaded, query the database
            $primaryImage = $this->images()->where('is_primary', true)->first();
            if ($primaryImage) {
                return $primaryImage->image_path;
            }
            
            $firstImage = $this->images()->first();
            if ($firstImage) {
                return $firstImage->image_path;
            }
        }
        
        // Fallback to old image field for backward compatibility
        return $this->image;
    }

    protected $casts = [
        'price' => 'decimal:2',
    ];

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
}
