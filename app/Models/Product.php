<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'products';

    protected $dates = [ 'deleted_at' ];

    protected $fillable = [
        'name',
        'category_id',
        'quantity',
        'price',
        'created_by'
    ];
}
