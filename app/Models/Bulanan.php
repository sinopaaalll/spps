<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bulanan extends Model
{
    /** @use HasFactory<\Database\Factories\BulananFactory> */
    use HasFactory;

    protected $table = 'bulanan';
    protected $guarded = ['id'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function bulan()
    {
        return $this->belongsTo(Bulan::class, 'bulan_id');
    }
}
