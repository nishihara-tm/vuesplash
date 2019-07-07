<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Photo;
use App\Comment;

class PhotoDetailApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCorrectJson()
    {
      factory(Photo::class)->create()->each(function($photo){
        $photo->comments()->saveMany(factory(Comment::class, 3)->make());  
      });
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
                'comments' => $photo->comments
                                    ->sortByDesc('id')
                                    ->map(function($comment){
                                      return [
                                        'author' => [
                                          'name' => $comment->author->name
                                        ],
                                        'content' => $comment->content,
                                      ];
                                    })->all(),
               ]);
    }
}
