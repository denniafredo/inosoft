<?php
namespace App\Services;
use App\Models\Transaksi;
use App\Models\Kendaraan;

class TransaksiService{

      private $rules = [
          'id_kendaraan' => 'required|string',
          'harga_jual' => 'required|numeric',
      ];
      public function getRules()
      {
        return $this->rules;
      }
      public function findAll()
     {
       $transaksi = Transaksi::with('kendaraan')->get();
       if(count($transaksi)==0){
        return null;
       }
       return $transaksi;
     }
      public function findById($id)
     {
       $transaksi = Transaksi::with('kendaraan')->where('id_kendaraan',$id)->first();
       return $transaksi;
     }
      public function create($data)
     {
        $transaksi = Transaksi::create([
           'id_kendaraan' => $data['id_kendaraan'],
           'harga_jual' => $data['harga_jual']
       ]);
       return $transaksi;
     }

     public function getReport()
     {
       $transaksi = $this->findAll();
       $report = [];
       foreach ($transaksi as $trx) {
        $report[] = $this->compileDataToReport($trx);
       }
       return $report;
     }

     public function compileDataToReport(Transaksi $transaksi)
     {
       $motorCollection = new Transaksi;
       $motorCollection->id_kendaraan = $transaksi->id_kendaraan;
       $motorCollection->harga_beli = $transaksi->kendaraan->harga;
       $motorCollection->harga_jual = $transaksi->harga_jual;
       $motorCollection->keuntungan = $transaksi->harga_jual-$transaksi->kendaraan->harga;
       $motorCollection->tanggal_terjual = $transaksi->created_at;
       return $motorCollection;
     }
     
}
