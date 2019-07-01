<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Photo;

class PhotoListApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCorrectJson(){
      factory(Photo::class, 5)->create();

      $response = $this->json('GET', route('photo.index'));

      $photos = Photo::with(['owner'])->orderBy('created_at', 'desc')->get();

      $expected_data = $photos->map(function($photo){
        return [
          'id' => $photo->id,
          'url' => $photo->url,
          'owner' => [
            'name' => $photo->owner->name
          ],
        ];
      })->all();

      $response->assertStatus(200)
               ->assertJsonCount(5, 'data');
    }
}
