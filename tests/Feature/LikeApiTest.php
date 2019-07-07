<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Photo;

class LikeApiTest extends TestCase
{
   use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void{
      parent::setUp();
      $this->user = factory(User::class)->create();
      factory(Photo::class)->create(); 

      $this->photo = Photo::first();
    }
    public function testLike()
    {
      $response = $this->actingAs($this->user)
                       ->json('PUT', route('photo.like', [
                         'photo' => $this->photo->id,
                       ]));
      $response->assertStatus(200)
               ->assertJsonFragment([
                'photo_id' => $this->photo->id
               ]);
      $this->assertEquals(1, $this->photo->likes()->count());
    }

    public function testLikeTwice() {
      $params = ['id' => $this->photo->id];

      $this->actingAs($this->user)->json('PUT', route('photo.like', $params));
      $this->actingAs($this->user)->json('PUT', route('photo.like', $params));

      $this->assertEquals(1, $this->photo->likes()->count());
    }

    public function testUnlike() {
      $this->photo->likes()->attach($this->user->id);

      $response = $this->actingAs($this->user)
        ->json('DELETE', route('photo.unlike', ['photo' => $this->photo->id]));

      $this->assertEquals(0, $this->photo->likes()->count());
    }
}
