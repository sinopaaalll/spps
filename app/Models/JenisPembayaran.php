<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPembayaran extends Model
{
    /** @use HasFactory<\Database\Factories\JenisPembayaranFactory> */
    use HasFactory;

    protected $table = 'jenis_pembayaran';
    protected $guarded = ['id'];

    public function pos()
    {
        return $this->belongsTo(Pos::class, 'pos_id');
    }

    public function tahun_ajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
