<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function should_return_user(){
      $data = [
        'name' => 'user',
        'email' => 'dummy@emaii.com',
        'password' => 'test1234',
        'password_confirmation' => 'test1234'
      ];

      $response = $this->json('POST', route('regsiter'), $data);

      $user = User::first();
      $this->assertEquals($data['name'], $user->name);

      $respose->assertStatus(201)
        ->assertJson(['name' => $user->name ]);
    }
}
