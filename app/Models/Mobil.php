<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;
    protected $collection = 'mobils';
    protected $fillable = ['mesin','kapasitas_penumpang','tipe'];

    public function kendaraan()
    {
        return $this->morphOne(Kendaraan::class, 'jenis');
    }
}
