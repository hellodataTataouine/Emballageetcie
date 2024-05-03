<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Define the relationship with the Product model through ProductVariation
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }



    public function product_variation()
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function refundRequest()
    {
        return $this->hasOne(Refund::class);
    }
}
