<?php
namespace App\Services;
use App\Models\Kendaraan;
use App\Models\Transaksi;

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

      public function getStock()
      {
        $transaksi = Transaksi::pluck('id_kendaraan')->all();
        $kendaraans = Kendaraan::with('jenis','transaksi')->whereNotIn('_id',$transaksi)->get();
        $stocks = new \stdClass;

        foreach ($kendaraans as $kendaraan) {
            $jenis = strtolower(explode('\\',get_class($kendaraan->jenis))[2]);
            if(!property_exists($stocks,$jenis)){
              $stocks->{$jenis} = 0;
          }
            $stocks->{$jenis}++;
        }
        return $stocks;
      }
     
}
