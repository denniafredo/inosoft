<?php

namespace Tests\Unit;

use App\Models\Kendaraan;
use App\Models\Transaksi;
use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
class TransaksiTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    protected function authenticate(){
        return JWTAuth::fromUser($this->createUser());
    }
    protected function createUser(){
        $user = User::where('name','sample')->first();
        if(!$user){
            $user = User::create([
                'name' => 'sample',
                'email' => 'sample@test.com',
                'password' => bcrypt('sample123'),
            ]);
        }
        return $user;
    }
    public function testGetReport()
    {
        $token = $this->authenticate();
        
        $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', 'api/transaksi/report')
        ->assertStatus(200)
        ->assertJsonStructure([
            "data" => [
                '*' => [
                    "id_kendaraan",
                    "harga_beli",
                    "harga_jual",
                    "keuntungan",
                    "tanggal_terjual"
                ]
            ]
        ]);
    }

    public function testJual()
    {
        $token = $this->authenticate();
        
        $kendaraan = Kendaraan::all()->take(1)->first();
        $payload = [
            'id_kendaraan' => $kendaraan->_id,
            'harga_jual' => '5000000'
        ];

        if(!Transaksi::where('id_kendaraan',$kendaraan->_id)->first()){
            $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->json('POST', 'api/transaksi', $payload)
            ->assertStatus(201)
            ->assertJsonStructure([
                "data" => [
                    "id_kendaraan",
                    "harga_jual",
                    "updated_at",
                    "created_at",
                    "_id"
                ]
            ]);
        }else{
            $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->json('POST', 'api/transaksi', $payload)
            ->assertStatus(409)
            ->assertJsonStructure([
                "message"
            ]);
        }
    }
}
