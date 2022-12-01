<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsinFile extends Model
{
    use HasFactory;

    protected $table = 'asin_file';

    protected $fillable = [
        'user_id',
        'filename',
        'added_date',
        'last_checked_date',
        'attempt_report'
    ];
}
