<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['email' => 'test@j-yado.com']);
    }

    public function test_登録済みのユーザーを認証して返却する()
    {
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJson(['name' => $this->user->name]);
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_メールを入力されていない場合エラーとなる()
    {
        $response = $this->json('POST', route('login'), [
            'email' => '',
            'password' => 'password'
        ]);

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'email' => ['メールは、必ず指定してください。']
            ]]);
    }

    public function test_メールが一致しない場合はエラーとなる()
    {
        $response = $this->json('POST', route('login'), [
            'email' => $this->faker->email,
            'password' => 'password'
        ]);

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'email' => ['認証情報と一致するレコードがありません。']
            ]]);
    }

    public function test_パスワードを入力ていない場合はエラーとなる()
    {
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => ''
        ]);

        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'password' => ['パスワードは、必ず指定してください。']
            ]]);
    }

    public function test_パスワードが一致してない場合メールのエラーとなる()
    {
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => '1234567'
        ]);
        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'email' => ['認証情報と一致するレコードがありません。']
            ]]);
    }
}
