<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bulan extends Model
{
    /** @use HasFactory<\Database\Factories\BulanFactory> */
    use HasFactory;

    protected $table = 'bulan';
    protected $guarded = ['id'];
}
