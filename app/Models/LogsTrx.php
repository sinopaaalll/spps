<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogsTrx extends Model
{
    protected $table = 'logs_trx';
    protected $guarded = ['id'];

    public function bulanan()
    {
        return $this->belongsTo(Bulanan::class, 'bulanan_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function bebas_pay()
    {
        return $this->belongsTo(BebasPay::class, 'bebas_pay_id');
    }
}
