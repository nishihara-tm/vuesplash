<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class LogoutApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp() : void {
      parent::setUp();

      $this->user = factory(User::class)->create();
    }

    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function should_logout_user(){
      $response = $this->actingAs($this->user)
        ->json('POST',  route('logout'));
      $response->assertStatus(200);
      $this->assertGuest();
    }
}
