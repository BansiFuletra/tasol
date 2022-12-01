<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputAsinFile extends Model
{
    use HasFactory;

    protected $table = 'output_asins_file';

    protected $fillable = [
        'user_id',
        'imported_file_id',
        'output_file_name',
        'save_date'
    ];
}
