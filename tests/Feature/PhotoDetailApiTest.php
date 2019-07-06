<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Photo;

class PhotoDetailApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCorrectJson()
    {
      factory(Photo::class)->create();
      $photo = Photo::first();

      $response = $this->json('GET', route('photo.show', [
        'id' => $photo->id,
      ]));

      $response->assertStatus(200)
               ->assertJsonFragment([
                'id' => $photo->id,
                'url' => $photo->url,
                'owner' => [
                  'name' => $photo->owner->name
                ],
               ]);
    }
}
