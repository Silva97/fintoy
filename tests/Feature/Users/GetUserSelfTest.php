<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Tests\TestCase;

class GetUserSelfTest extends TestCase
{
    public function test_get_user_self_data()
    {
        /** @var User */
        $user = User::factory()->create();
        $token = auth()->login($user);

        $this->get('/users/self', [
            'Authorization' => "Bearer $token",
        ])
            ->assertOk()
            ->assertExactJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_shopkeeper' => $user->is_shopkeeper,
                'identification_number' => $user->identification_number,
            ]);
    }
}
