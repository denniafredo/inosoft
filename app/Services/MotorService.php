<?php
namespace App\Services;
use App\Models\Kendaraan;
use App\Models\Motor;
use App\Models\Transaksi;

class MotorService{
  private $rules = [
    'mesin' => 'required|string',
    'tipe_suspensi' => 'required|string',
    'tipe_transmisi' => 'required|string',
  ];
  public function getRules()
      {
        $kendaraanRules = new KendaraanService;
        return array_merge((array) $this->rules, (array) $kendaraanRules->getRules());

      }
    public function findAll()
     {
       $motors = Motor::with('kendaraan')->get();
       if(count($motors)==0){
        return null;
       }
       return $motors;
     }

     public function findById($id)
     {
       $motor = Motor::with('kendaraan')->where('_id',$id)->first();
       return $motor;
     }
    public function create($data)
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
     public function deleteById($id)
     {
        $response = new \stdClass;

        if($motor = $this->findById($id)){
          $trx = Transaksi::where($motor->kendaraan->_id)->first();
          if(!$trx){
            $motor->kendaraan()->delete();
            $motor->delete();
            return $motor;
          }
          else {
            $response->message = 'Motor Already Sold';
            $response->code = 409;
            return $response;
          }
        }
        $response->message = 'Motor Not Found';
        $response->code = 404;
        return $response;
    }

     public function updateById($id,$data)
     {
        $motor = $this->findById($id);
        if(!$motor){
          return null;
        }
        $motor->update([
            'mesin' => $data['mesin'],
            'tipe_suspensi' => $data['tipe_suspensi'],
            'tipe_transmisi' => $data['tipe_transmisi']
        ]);

        $motor->kendaraan()->update([
            'tahun_keluaran' => $data['tahun_keluaran'],
            'warna' => $data['warna'],
            'harga' => $data['harga']
        ]);
        $kendaraanById = Kendaraan::with('jenis')->where("jenis_id",$id)->first();
        return $kendaraanById;
      
     }
     public function recompileData(Motor $motor)
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
