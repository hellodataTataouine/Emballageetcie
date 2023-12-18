<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductParents extends Model
{
   
        protected $table = 'product_parent'; // Set the table name explicitly
    
        protected $fillable = [
            'product_id',
            'child_id',
        ];
    
        // Define the relationships with the Product model
        public function product()
        {
            return $this->belongsTo(Product::class, 'product_id', 'id');
        }
    
        public function childProduct()
        {
            return $this->belongsTo(Product::class, 'child_id');
        }
    }

