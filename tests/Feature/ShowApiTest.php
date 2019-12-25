<?php

namespace Tests\Feature;

use App\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ShowApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $photo;

    protected function setUp(): void
    {
        parent::setUp();
        \Storage::fake('azure');
        \Storage::cloud()->putFileAs('/', UploadedFile::fake()->image('image.jpg'), 'image.jpg');
        $this->photo = factory(Photo::class)->create();
    }

    public function test_正しいデータを返ってくる()
    {
        $response = $this->json('GET', route('photo.show', 1));
        $response->assertStatus(200)
            ->assertJson([
                'image_path' => $this->photo->image_path,
                'image_name' =>$this->photo->filename
            ]);
    }

    public function test_データが存在していない場合は404が返る()
    {
        $response = $this->json('GET', route('photo.show', $this->photo->id + 1));
        $response->assertStatus(404);
    }
}
