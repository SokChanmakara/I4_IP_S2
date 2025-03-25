<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'customer_id',
        'order_date',
        'total_price',
    ];

    public function payment()
    {
        return $this-> hasMany(Payment::class);
    }
    public function customer()
    {
        return $this-> belongsTo(Customer::class);
    }
    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class);
    }
    protected function orderDate(): Attribute
    {
        return Attribute::make(
            // Mutator: Convert input format to MySQL format before saving
            set: fn($value) => Carbon::createFromFormat('d/m/y H:i:s', $value)-> format('Y-m-d H:i:s'),

            //Accessor: Convert database format to user format when retrieving
            get: fn($value) => Carbon::parse($value)->format('d/m/y H:i:s')
        );
    }
}
