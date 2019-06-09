<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void{
      parent::setUp();

      $this->user = factory(User::class)->create();
    }
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

    public function test_return_user(){
      $response = $this->json('POST', route('login'), [
        'email' => $this->user->email,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
      ]);

      $response->assertStatus(200)->assertJson(['name' => $this->user->name]);
      $this->assertAuthenticatedAs($this->user);
    }
}
