<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use WithFaker;

    public function test_create_new_common_user()
    {
        $userData = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'is_shopkeeper' => false,
            'identification_number' => $this->faker->numerify('###########'),
            'password' => 'password123456',
        ];

        $response = $this->postJson('/users', $userData);

        $response
            ->assertCreated()
            ->assertExactJson([
                'id' => $response->json('id'),
                'name' => $userData['name'],
                'email' => $userData['email'],
                'is_shopkeeper' => $userData['is_shopkeeper'],
                'identification_number' => $userData['identification_number'],
            ]);

        $user = User::find($response->json('id'));
        $this->assertNotNull($user);

        $this->assertNotNull($user->wallet);
        $this->assertEquals(0, $user->wallet->balance);
    }

    public function test_create_new_shopkeeper_user()
    {
        $userData = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'is_shopkeeper' => true,
            'identification_number' => $this->faker->numerify('##############'),
            'password' => 'password123456',
        ];

        $response = $this->postJson('/users', $userData);

        $response
            ->assertCreated()
            ->assertExactJson([
                'id' => $response->json('id'),
                'name' => $userData['name'],
                'email' => $userData['email'],
                'is_shopkeeper' => $userData['is_shopkeeper'],
                'identification_number' => $userData['identification_number'],
            ]);

        $user = User::find($response->json('id'));
        $this->assertNotNull($user);

        $this->assertNotNull($user->wallet);
        $this->assertEquals(0, $user->wallet->balance);
    }

    public function test_create_user_with_duplicated_email_expects_422()
    {
        $userData = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'is_shopkeeper' => false,
            'identification_number' => $this->faker->numerify('###########'),
            'password' => 'password123456',
        ];

        $this->postJson('/users', $userData)
            ->assertCreated();

        $userData['identification_number'] = $this->faker->numerify('###########');

        $this->postJson('/users', $userData)
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ],
            ]);
    }

    public function test_create_user_with_duplicated_id_number_expects_422()
    {
        $userData = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'is_shopkeeper' => false,
            'identification_number' => $this->faker->numerify('###########'),
            'password' => 'password123456',
        ];

        $this->postJson('/users', $userData)
            ->assertCreated();

        $userData['email'] = $this->faker->unique()->safeEmail();

        $this->postJson('/users', $userData)
            ->assertUnprocessable()
            ->assertJson([
                'errors' => [
                    'identification_number' => ['The identification number has already been taken.'],
                ],
            ]);
    }
}
