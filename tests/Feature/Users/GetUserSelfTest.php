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
        $this->actingAs($user);

        $this->get('/users/self')
            ->assertOk()
            ->assertExactJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_shopkeeper' => $user->is_shopkeeper,
                'identification_number' => $user->identification_number,
                'balance' => 9001,
            ]);
    }

    public function test_try_to_get_user_self_data_unauthenticated_expects_401()
    {
        $this->get('/users/self')
            ->assertUnauthorized();
    }
}
