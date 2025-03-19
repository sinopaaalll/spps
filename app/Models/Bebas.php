<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bebas extends Model
{
    /** @use HasFactory<\Database\Factories\BebasFactory> */
    use HasFactory;

    protected $table = 'bebas';
    protected $guarded = ['id'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
