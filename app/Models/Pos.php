<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos extends Model
{
    /** @use HasFactory<\Database\Factories\PosFactory> */
    use HasFactory;

    protected $table = 'pos';
    protected $guarded = ['id'];
}
