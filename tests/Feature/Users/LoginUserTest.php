<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    public function test_user_login()
    {
        $user = User::factory()->create();

        $this->postJson('/users/login', [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }

    public function test_try_to_login_with_wrong_password_expects_401()
    {
        $user = User::factory()->create();

        $this->postJson('/users/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
            ->assertUnauthorized()
            ->assertExactJson([]);
    }

    public function test_try_to_login_with_wrong_email_expects_401()
    {
        $this->postJson('/users/login', [
            'email' => 'wrong_email@test.com',
            'password' => 'password',
        ])
            ->assertUnauthorized()
            ->assertExactJson([]);
    }
}
