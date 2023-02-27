<?php
namespace App\Services;
use App\Models\Kendaraan;
use App\Models\Mobil;
use Illuminate\Database\Eloquent\Collection;

class KendaraanService{

      private $rules = [
          'tahun_keluaran' => 'required|numeric',
          'warna' => 'required|string',
          'harga' => 'required|numeric',
      ];
      public function getRules()
      {
        return $this->rules;
      }
     
}
