<?php
namespace App\Services;
use App\Models\Kendaraan;
use App\Models\Motor;
use Illuminate\Database\Eloquent\Collection;

class MotorService{
    public function findAll() : Collection
     {
       $motors = Motor::with('kendaraan')->get();
       $motorCollection = new Collection;
       if(count($motors)==0){
        return new Collection;
       }
       foreach($motors as $motor){
          $motorCollection[] = Self::recompileData($motor);
       }
       return $motorCollection;
     }

     public function findById($id) : Motor
     {
       $motor = Motor::with('kendaraan')->where('_id',$id)->first();
       if(!$motor){
        return new Motor;
       }
       $motorCollection = Self::recompileData($motor);
       return $motorCollection;
     }
    public function create($data) : Kendaraan
     {
       $motor = Motor::create([
           'mesin' => $data['mesin'],
           'tipe_suspensi' => $data['tipe_suspensi'],
           'tipe_transmisi' => $data['tipe_transmisi']
       ]);

       $kendaraan = new Kendaraan;
       $kendaraan->tahun_keluaran = $data['tahun_keluaran'];
       $kendaraan->warna = $data['warna'];
       $kendaraan->harga = $data['harga'];

       $dataCreated = Motor::find($motor->_id)->kendaraan()->save($kendaraan);
       $kendaraanById = Kendaraan::with('jenis')->where("_id",$dataCreated->_id)->first();
       return $kendaraanById;
     }
     public function deleteById($id) : Motor
     {
       $motor = Motor::with('kendaraan')->where('_id',$id)->first();
       if(!$motor){
         $motor = new Motor;
         $motor->errorCode = '404';
         return $motor;
       }
       
       $motor->kendaraan()->delete();
       $motor->delete();
       return $motor;
     }
     public function updateById($id,$data) : Kendaraan
     {
      $motor = Motor::with('kendaraan')->where('_id',$id)->first();
       if(!$motor){
         $kendaraan = new Kendaraan;
         $kendaraan->errorCode = '404';
         return $kendaraan;
       }
       $motor->update([
           'mesin' => $data['mesin'],
           'tipe_suspensi' => $data['tipe_suspensi'],
           'tipe_transmisi' => $data['tipe_transmisi']
       ]);

       $dataUpdated= $motor->kendaraan()->update([
          'tahun_keluaran' => $data['tahun_keluaran'],
          'warna' => $data['warna'],
          'harga' => $data['harga']
       ]);
       $kendaraanById = Kendaraan::with('jenis')->where("jenis_id",$id)->first();
       return $kendaraanById;
     }
     public function recompileData(Motor $motor):Motor
     {
       $motorCollection = new Motor;
       $motorCollection->id = $motor->_id;
       $motorCollection->tahun_keluaran = $motor->kendaraan->tahun_keluaran;
       $motorCollection->warna = $motor->kendaraan->warna;
       $motorCollection->harga = $motor->kendaraan->harga;
       $motorCollection->mesin = $motor->mesin;
       $motorCollection->tipe_suspensi = $motor->tipe_suspensi;
       $motorCollection->tipe_transmisi = $motor->tipe_transmisi;
       return $motorCollection;
     }
     
}
