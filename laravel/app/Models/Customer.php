<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'address',
        'phone'
    ];
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function order()
    {
        return $this->hasMany(Order::class);
    }
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
    public function products()
    {
        return $this->hasManyThrough(Product::class, Cart::class);
    }
}
