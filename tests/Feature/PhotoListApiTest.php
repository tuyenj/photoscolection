<?php

namespace Tests\Feature;

use App\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PhotoListApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $photos;

    protected function setUp(): void
    {
        parent::setUp();

        $this->photos = factory(Photo::class, 15)->create();
    }

    public function test_全部のデータの数を返却する()
    {
        $response = $this->json('GET', route('photo.index'));
        $count_data = \DB::table('photos')->count();
        $this->assertEquals($count_data, json_decode($response->content())->total);
    }

    public function test_正しいJSONを返却する()
    {
        $response = $this->json('GET', route('photo.index'));

        $this->photos = $this->photos->slice(0, $this->photos->first()->getPerPage());

        $expected_data = $this->photos->map(function ($photo) {
            return [
                'id' => $photo->id,
                'image_path' => $photo->image_path,
                'owner' => ['name' => $photo->owner->name]
            ];
        })->all();

        $response->assertStatus(200)
            ->assertJsonFragment(['data' => $expected_data]);
    }
}
