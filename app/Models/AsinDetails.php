<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsinDetails extends Model
{
    use HasFactory;

    protected $table = 'asin_details';

    protected $fillable = [
        'user_id',
        'import_file_id',
        'output_file_id',
        'asin',
        'currency',
        'prime_price',
        'prime_price_lowest',
        'prime_inventory',
        'lowest_buy_box_price',
        'lowest_price_is_prime',
        'seller_name',
        'seller_rating',
        'handling_time',
        'shipping_price',
        'number_of_seller'
    ];
}
