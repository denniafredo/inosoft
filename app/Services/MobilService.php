<?php
namespace App\Services;
use App\Models\Kendaraan;
use App\Models\Mobil;
use Illuminate\Database\Eloquent\Collection;

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
       $mobilCollection = new Collection;
       if(count($mobils)==0){
        return new Collection;
       }
       foreach($mobils as $mobil){
          $mobilCollection[] = Self::recompileData($mobil);
       }
       return $mobilCollection;
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
        if($mobil = $this->findById($id)){
          $mobil->kendaraan()->delete();
          $mobil->delete();
          return $mobil;
        }
        return null;
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
