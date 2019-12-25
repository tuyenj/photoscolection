<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PhotoSubmitApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        \Storage::fake('azure');
        $this->user = factory(User::class)->create();
    }

    public function test_ファイルをアップロードができる()
    {
        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'image' => UploadedFile::fake()->image('photo.jpg')
            ]);
        $response->assertStatus(201);

        $photo = Photo::first();

        \Storage::cloud()->assertExists($photo->filename);

    }

    public function test_認証がされていない場合エラーとなる()
    {
        $response = $this->json('POST', route('photo.create'), [
            'image' => UploadedFile::fake()->image('photo.jpg')
        ]);
        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
        $this->assertCount(0, \Storage::cloud()->files());
    }

    public function test_エラーを発生した場合は写真の登録されていない()
    {
        \Schema::drop('photos');
        $response = $this->actingAs($this->user)->json('POST', route('photo.create'), [
            'image' => UploadedFile::fake()->image('photo.jpg')
        ]);
        $response->assertStatus(500);
        $this->assertCount(0, \Storage::cloud()->files());
    }

    public function test_写真を登録しない場合はエラーとなる()
    {
        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'image' => ''
            ]);
        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'image' => ['写真は、必ず指定してください。']
            ]]);
        $this->assertCount(0, \Storage::cloud()->files());
    }

    public function test_拡張子が画像でない場合はエラーとなる()
    {
        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'image' => UploadedFile::fake()->create('test.pdf')
            ]);
        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'image' => ['写真には、jpg, jpeg, png, gifタイプのファイルを指定してください。']
            ]]);
        $this->assertCount(0, \Storage::cloud()->files());
    }

}
