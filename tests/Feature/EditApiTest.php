<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EditApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $user;
    private $photo;

    protected function setUp(): void
    {
        parent::setUp();
        \Storage::fake('azure');
        \Storage::cloud()->putFileAs('/', UploadedFile::fake()->image('image.jpg'), 'image.jpg');
        $this->photo = factory(Photo::class)->create(['filename' => 'image.jpg']);
        $this->user = factory(User::class)->create();
    }

    public function test_編集ができる()
    {
        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.update', 1), [
                'image' => UploadedFile::fake()->image('image.jpg')
            ]);
        $response->assertStatus(200);
        $this->assertCount(2, \Storage::cloud()->files());
    }

    public function test_画像が入力されていない場合はできる()
    {
        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.update', 1), [
                'image' => ''
            ]);
        $response->assertStatus(200);
    }

    public function test_認証されいない場合はエラーとなる()
    {
        $response = $this->json('POST', route('photo.update', 1), [
            'image' => UploadedFile::fake()->image('image.jpg')
        ]);
        $response->assertStatus(401);

        $response->assertJson(['message' => 'Unauthenticated.']);

        // 写真がアップロードされていない
        $this->assertCount(1, \Storage::cloud()->files());
    }

    public function test_拡張子が画像でない場合はエラーとなる()
    {
        $response = $this->actingAs($this->user)->json('POST', route('photo.update', 1), [
            'image' => UploadedFile::fake()->create('test.doc')
        ]);
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'image' => ['写真には、jpg, jpeg, png, gifタイプのファイルを指定してください。']
                ]
            ]);

        // 写真がアップロードされていない
        $this->assertCount(1, \Storage::cloud()->files());
    }

    public function test_エラーを発生した場合はデータを保存していない()
    {
        \Schema::drop('photos');
        $response = $this->actingAs($this->user)->json('POST', route('photo.update', 1), [
            'image' => UploadedFile::fake()->image('image.jpg')
        ]);

        $response->assertStatus(500);

        // 写真がアップロードされていない
        $this->assertCount(1, \Storage::cloud()->files());
    }
}
