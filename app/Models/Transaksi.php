<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;
    protected $collection = 'transaksi';
    protected $fillable = ['id_kendaraan','harga_jual'];

    public function kendaraan()
    {
        return $this->morphTo();
    }
}
