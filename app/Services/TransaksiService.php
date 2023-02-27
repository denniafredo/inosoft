<?php
namespace App\Services;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Collection;

class TransaksiService{

      private $rules = [
          'id_kendaraan' => 'required|string',
          'harga_jual' => 'required|numeric',
      ];
      public function getRules()
      {
        return $this->rules;
      }

      public function create($data)
     {
       $transaksi = Transaksi::create([
           'id_kendaraan' => $data['id_kendaraan'],
           'harga_jual' => $data['harga_jual']
       ]);
       return $transaksi;
     }
     
}
