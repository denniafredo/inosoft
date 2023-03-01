<?php
namespace App\Services;
use App\Models\Kendaraan;
use App\Models\Mobil;
use App\Models\Transaksi;

class MobilService{
  private $rules = [
    'mesin' => 'required|string',
    'kapasitas_penumpang' => 'required|numeric',
    'tipe' => 'required|string',
  ];
  public function getRules()
      {
        $kendaraanRules = new KendaraanService;
        return array_merge((array) $this->rules, (array) $kendaraanRules->getRules());

      }
    public function findAll()
     {
       $mobils = Mobil::with('kendaraan')->get();
       if(count($mobils)==0){
        return null;
       }
       return $mobils;
     }

     public function findById($id)
     {
       $mobil = Mobil::with('kendaraan')->where('_id',$id)->first();
       return $mobil;
     }
    public function create($data)
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
     public function deleteById($id)
     {
      $response = new \stdClass;

      if($mobil = $this->findById($id)){
        $trx = Transaksi::where($mobil->kendaraan->_id)->first();
        if(!$trx){
          $mobil->kendaraan()->delete();
          $mobil->delete();
          return $mobil;
        }
        else {
          $response->message = 'Mobil Already Sold';
          $response->code = 409;
          return $response;
        }
      }
      $response->message = 'Mobil Not Found';
      $response->code = 404;
      return $response;
    }

     public function updateById($id,$data)
     {
        $mobil = $this->findById($id);
        if(!$mobil){
          return null;
        }
        $mobil->update([
            'mesin' => $data['mesin'],
            'kapasitas_penumpang' => $data['kapasitas_penumpang'],
            'tipe' => $data['tipe']
        ]);

        $mobil->kendaraan()->update([
            'tahun_keluaran' => $data['tahun_keluaran'],
            'warna' => $data['warna'],
            'harga' => $data['harga']
        ]);
        $kendaraanById = Kendaraan::with('jenis')->where("jenis_id",$id)->first();
        return $kendaraanById;
      
     }
     public function recompileData(Mobil $mobil)
     {
       $mobilCollection = new Mobil;
       $mobilCollection->id = $mobil->_id;
       $mobilCollection->tahun_keluaran = $mobil->kendaraan->tahun_keluaran;
       $mobilCollection->warna = $mobil->kendaraan->warna;
       $mobilCollection->harga = $mobil->kendaraan->harga;
       $mobilCollection->mesin = $mobil->mesin;
       $mobilCollection->kapasitas_penumpang = $mobil->kapasitas_penumpang;
       $mobilCollection->tipe = $mobil->tipe;
       return $mobilCollection;
     }
     
}
