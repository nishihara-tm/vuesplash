<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Photo;

class AddCommentApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void {
      parent::setUp();
      $this->user = factory(User::class)->create();
    }

    public function testAddComment(){
      factory(Photo::class)->create();
      $photo = Photo::first();
      $content = "Sample content";

      $response = $this->actingAs($this->user)
        ->json('POST', route('photo.comment', [ 'photo' => $photo->id ]), compact('content'));

      $comments = $photo->comments()->get();
      $response->assertStatus(201)
               ->assertJsonFragment([
                 "author" => [
                   "name" => $this->user->name
                 ],
                 "content" => $content
               ]);

      $this->assertEquals(1, $comments->count());
      $this->assertEquals($content, $comments[0]->content);
    }
}
