<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;


class KendaraanTest extends TestCase
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
    public function testGetStokKendaraan()
    {
        $token = $this->authenticate();
        $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', 'api/kendaraans/stock')
        ->assertStatus(200)
        ->assertJsonStructure([
            "data",
        ]);    
    }
}
