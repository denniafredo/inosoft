<?php

namespace Tests\Unit;

use App\Models\Motor;
use App\Models\Transaksi;
use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class MotorTest extends TestCase
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
    public function testGetMotors()
    {
        $token = $this->authenticate();
        
        $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', 'api/motors')
        ->assertStatus(200)
        ->assertJsonStructure([
            "data" => [
                '*' => [
                    "id",
                    "tahun_keluaran",
                    "warna",
                    "harga",
                    "mesin",
                    "tipe_suspensi",
                    "tipe_transmisi"
                ]
            ]
        ]);
    }

    public function testGetMotorsById()
    {
        $token = $this->authenticate();
        $motor = Motor::all()->take(1)->first();
        if($motor){
        $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', 'api/motors/'.$motor->_id)
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                        "id",
                        "tahun_keluaran",
                        "warna",
                        "harga",
                        "mesin",
                        "tipe_suspensi",
                        "tipe_transmisi"
                ]
            ]);
        }
        else{
            $this->withHeaders([
                'Authorization' => 'Bearer '. $token,
            ])->json('GET', 'api/motors/123')
            ->assertStatus(404)
            ->assertJson([
                "message"=>"Motor Not Found",
                "data" =>""
            ]);
        }
    }

    public function testDeleteMotorsById()
    {
        $token = $this->authenticate();
        $motor = Motor::all()->take(1)->first();
        $trx = Transaksi::where($motor->kendaraan->_id)->first();
        if($trx){
            $this->withHeaders([
                'Authorization' => 'Bearer '. $token,
            ])->json('DELETE', 'api/motors/'.$motor->_id)
            ->assertStatus(409);
        }
        else{
            $this->withHeaders([
                'Authorization' => 'Bearer '. $token,
            ])->json('DELETE', 'api/motors/'.$motor->_id)
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                        "id",
                        "tahun_keluaran",
                        "warna",
                        "harga",
                        "mesin",
                        "tipe_suspensi",
                        "tipe_transmisi"
                ]
            ]);
        }
    }

    public function testCreateMotor()
    {
        $token = $this->authenticate();
        $payload = [
            "tahun_keluaran" => 2000,
            "warna" => "hitam",
            "harga" => 2000000,
            "mesin" => "baru",
            "tipe_suspensi" => "A",
            "tipe_transmisi" => "B"
        ];

        
        $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', 'api/motors/',$payload)
        ->assertStatus(201)
        ->assertJsonStructure([
            "data" => [
                    "_id",
                    "tahun_keluaran",
                    "warna",
                    "harga",
                    "jenis_id",
                    "jenis_type",
                    "updated_at",
                    "created_at",
                    "jenis" => [
                        "_id",
                        "mesin",
                        "tipe_suspensi",
                        "tipe_transmisi",
                        "updated_at",
                        "created_at",
                    ]
            ]
        ]);
    }

    public function testupdateMotor()
    {
        $token = $this->authenticate();
        $motor = Motor::all()->take(1)->first();

        $payload = [
            "tahun_keluaran" => 2500,
            "warna" => "hitam",
            "harga" => 2000000,
            "mesin" => "baru",
            "tipe_suspensi" => "A",
            "tipe_transmisi" => "B"
        ];

        
        $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('PUT', 'api/motors/'.$motor->_id,$payload)
        ->assertStatus(200)
        ->assertJsonStructure([
            "data" => [
                    "_id",
                    "tahun_keluaran",
                    "warna",
                    "harga",
                    "jenis_id",
                    "jenis_type",
                    "updated_at",
                    "created_at",
                    "jenis" => [
                        "_id",
                        "mesin",
                        "tipe_suspensi",
                        "tipe_transmisi",
                        "updated_at",
                        "created_at",
                    ]
            ]
        ]);
    }
}
