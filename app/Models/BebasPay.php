<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BebasPay extends Model
{
    protected $table = 'bebas_pay';
    protected $guarded = ['id'];

    public function bebas()
    {
        return $this->belongsTo(Bebas::class, 'bebas_id');
    }
}
