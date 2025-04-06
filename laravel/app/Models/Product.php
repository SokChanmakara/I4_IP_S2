<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'category_id',
        'pricing',
        'description',
        'images',
    ];
    protected $casts = [
        'images' => 'array', // Automatically cast the 'images' JSONB column to/from an array
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}