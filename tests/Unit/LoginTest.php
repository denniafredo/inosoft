<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginTest extends TestCase
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
    public function testMustEnterEmailAndPassword(){
        $this->json('POST', 'api/auth/login')
        ->assertStatus(422)
        ->assertJson([
            "errors" => [
                'email' => ["The email field is required."],
                'password' => ["The password field is required."],
            ]
        ]);
    }
    public function testSuccessfulLogin(){
        $this->createUser();
        $user=[
            'email' => 'sample@test.com',
            'password' =>'sample123',
        ];

        $this->json('POST', 'api/auth/login', $user)
            ->assertStatus(200)
            ->assertJsonStructure([
                "success",
                "token" => [
                   'headers',
                   'original' => [
                        "access_token",
                        "token_type",
                        "expires_in"
                    ],
                   'exception'
                ]
            ]);
    }
    public function testSuccessfullyLogout(){
        $token = $this->authenticate();
        $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', 'api/auth/logout')
        ->assertStatus(200)
        ->assertJson([
            "message" => "Successfully logged out!"
        ]);
    }
}
