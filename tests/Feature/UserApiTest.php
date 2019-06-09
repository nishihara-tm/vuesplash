<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class UserApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp() : void {
      parent::setUp();

      $this->user = factory(User::class)->create();
    }

    public function test_login_user() {
      $response = $this->actingAs($this->user)->json('GET', route('user'));

      $response->assertStatus(200)->assertJson(['name' => $this->user->name ]);
    }

    public function test_not_login_user() {
      $response = $this->json('GET', route('user'));
      $response->assertStatus(200);
      $this->assertEquals("", $response->content());
    }
}
