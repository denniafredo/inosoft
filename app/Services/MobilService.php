<?php
namespace App\Services;
use App\Models\Kendaraan;
use App\Models\Mobil;
use Illuminate\Database\Eloquent\Collection;

class MobilService{
    public function findAll() : Collection
     {
       $mobils = Mobil::with('kendaraan')->get();
       $mobilCollection = new Collection;
       if(count($mobils)==0){
        return new Collection;
       }
       foreach($mobils as $mobil){
          $mobilCollection[] = Self::recompileData($mobil);
       }
       return $mobilCollection;
     }

     public function findById($id) : Mobil
     {
       $mobil = Mobil::with('kendaraan')->where('_id',$id)->first();
       if(!$mobil){
        return new Mobil;
       }
       $mobilCollection = Self::recompileData($mobil);
       return $mobilCollection;
     }
    public function create($data) : Kendaraan
     {
       $mobil = Mobil::create([
           'mesin' => $data['mesin'],
           'kapasitas_penumpang' => $data['kapasitas_penumpang'],
           'tipe' => $data['tipe']
       ]);

       $kendaraan = new Kendaraan;
       $kendaraan->tahun_keluaran = $data['tahun_keluaran'];
       $kendaraan->warna = $data['warna'];
       $kendaraan->harga = $data['harga'];

       $dataCreated = Mobil::find($mobil->_id)->kendaraan()->save($kendaraan);
       $kendaraanById = Kendaraan::with('jenis')->where("_id",$dataCreated->_id)->first();
       return $kendaraanById;
     }
     public function deleteById($id) : Mobil
     {
       $mobil = Mobil::with('kendaraan')->where('_id',$id)->first();
       if(!$mobil){
         $mobil = new Mobil;
         $mobil->errorCode = '404';
         return $mobil;
       }
       
       $mobil->kendaraan()->delete();
       $mobil->delete();
       return $mobil;
     }
     public function updateById($id,$data) : Kendaraan
     {
      $mobil = Mobil::with('kendaraan')->where('_id',$id)->first();
       if(!$mobil){
         $kendaraan = new Kendaraan;
         $kendaraan->errorCode = '404';
         return $kendaraan;
       }
       $mobil->update([
        'mesin' => $data['mesin'],
        'kapasitas_penumpang' => $data['kapasitas_penumpang'],
        'tipe' => $data['tipe']
       ]);

       $dataUpdated= $mobil->kendaraan()->update([
          'tahun_keluaran' => $data['tahun_keluaran'],
          'warna' => $data['warna'],
          'harga' => $data['harga']
       ]);
       $kendaraanById = Kendaraan::with('jenis')->where("jenis_id",$id)->first();
       return $kendaraanById;
     }
     public function recompileData(Mobil $mobil):Mobil
     {
       $mobilCollection = new Mobil;
       $mobilCollection->id = $mobil->_id;
       $mobilCollection->tahun_keluaran = $mobil->kendaraan->tahun_keluaran;
       $mobilCollection->warna = $mobil->kendaraan->warna;
       $mobilCollection->harga = $mobil->kendaraan->harga;
       $mobilCollection->mesin = $mobil->mesin;
       $mobilCollection->tipe_suspensi = $mobil->tipe_suspensi;
       $mobilCollection->tipe_transmisi = $mobil->tipe_transmisi;
       return $mobilCollection;
     }
     
}
