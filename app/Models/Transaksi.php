<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $collection = 'transaksi';
    protected $fillable = ['id_kendaraan','harga_jual'];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class,'id_kendaraan','_id');
    }
}
