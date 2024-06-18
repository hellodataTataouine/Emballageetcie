<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    // Optionally specify the table name if it's different from 'visits'
    protected $table = 'visits';
    public $timestamps = true; 
    protected $fillable = [
        'route_name',
        'country',
    ];

}