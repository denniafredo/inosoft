<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;
    protected $collection = 'kendaraans';
    protected $fillable = ['tahun_keluaran','warna','harga'];

    public function jenis()
    {
        return $this->morphTo();
    }
    public function transaksi()
    {
        return $this->hasOne(Transaksi::class);
    }
}
