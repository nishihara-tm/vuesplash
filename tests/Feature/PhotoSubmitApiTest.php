<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoSubmitApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void{
      parent::setUp();

      $this->user = factory(User::class)->create();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUploadPhoto()
    {
      Storage::fake('s3');
      
      \Log::info(UploadedFile::fake()->image('photo.jpg'));
      $response= $this->actingAs($this->user)
                      ->json('POST', route('photo.create'), [
                        'photo' => UploadedFile::fake()->image('photo.jpg'),
                      ]);

      $response->assertStatus(201);

      $photo= Photo::first();

      $this->assertRegExp('/^[0-9a-zA-Z-_]{12}$/', $photo->id);

      Storage::cloud()->assertExists($photo->filename);
    }

    public function testFailAndNotUpload()
    {
        // ストレージをモックして保存時にエラーを起こさせる
        Storage::shouldReceive('cloud')
            ->once()
            ->andReturnNull();

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        // レスポンスが500(INTERNAL SERVER ERROR)であること
        $response->assertStatus(500);

        // データベースに何も挿入されていないこと
        $this->assertEmpty(Photo::all());
    }
}
