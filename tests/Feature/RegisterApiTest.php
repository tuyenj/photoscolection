<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_新しいユーザーを作成して返却するのができる()
    {
        $data = [
            'name' => 'user',
            'email' => 'user@email.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234'
        ];

        $response = $this->json('POST', route('register'), $data);

        $user = User::first();
        $this->assertEquals($data['name'], $user->name);

        $response->assertStatus(201)
            ->assertJson(['name' => $user->name]);
    }

    public function test_名前が入力されていない場合エラーとなる()
    {
        $data = ['name' => ''];
        $response = $this->json('POST', route('register'), $data);
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => [
                        '名前は、必ず指定してください。'
                    ]
                ]
            ]);
    }

    public function test_名前は256文字以上場合エラーとなる()
    {
        $data = ['name' => str_repeat('a', 256)];

        $response = $this->json('POST', route('register'), $data);
        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'name' => ['名前は、255文字以下にしてください。']
            ]]);
    }

    public function test_メールが入力されていない場合エラーとなる()
    {
        $data = ['email' => ''];

        $response = $this->json('POST', route('register'), $data);
        $response->assertStatus(422)
            ->assertJson(['errors' => [
                'email' => [
                    'メールは、必ず指定してください。'
                ]
            ]]);
    }

    public function test_メールのフォーマットが間違い場合エラーとなる()
    {
        $data = ['email' => 'test_mail'];

        $response = $this->json('POST', route('register'), $data);
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'メールは、有効なメールアドレス形式で指定してください。'
                    ]
                ]
            ]);
    }

    public function test_メールの入力は256文字以上場合エラーとなる()
    {
        $data = ['email' => str_repeat('b', 256) . '@mail.com'];

        $response = $this->json('POST', route('register'), $data);
        $response->assertStatus(422)
            ->assertJson(
                [
                    'errors' => [
                        'email' => ['メールは、255文字以下にしてください。']
                    ]
                ]);
    }

    public function test_メールは重複する場合エラーとなる()
    {
        factory(User::class)->create(['email' => 'test@mail.com']);
        $data = ['email' => 'test@mail.com'];

        $response = $this->json('POST', route('register'), $data);
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => [
                        '指定のメールは既に使用されています。'
                    ]
                ]
            ]);
    }

    public function test_パスワードが入力されていない場合エラーとなる()
    {
        $data = ['password' => ''];

        $response = $this->json('POST', route('register'), $data);
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'password' => [
                        'パスワードは、必ず指定してください。'
                    ]
                ]
            ]);
    }

    public function test_パスワードの入力は7文字以下の場合エラーとなる()
    {
        $data = ['password' => 'abc1234'];

        $response = $this->json('POST', route('register'), $data);
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'password' => [
                        'パスワードは、8文字以上にしてください。'
                    ]
                ]
            ]);
    }

    public function test_パスワードは確認パスワードと違う場合エラーとなる()
    {
        $data = [
            'password' => 'abcd1234',
            'password_confirmation' => '1234abcd'
        ];

        $response = $this->json('POST', route('register'), $data);
        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'password' => [
                        'パスワードとパスワード確認が一致しません。'
                    ]
                ]
            ]);
    }
}
