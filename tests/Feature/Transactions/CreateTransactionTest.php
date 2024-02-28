<?php

namespace Tests\Feature\Transactions;

use App\Clients\AuthorizingServiceClient;
use App\Jobs\NotifyTransactionJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateTransactionTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake();

        $mockedAuthorizingServiceInstance = Mockery::mock(
            AuthorizingServiceClient::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('hasAuthorization')
                    ->andReturn(true);
            }
        );

        $this->instance(AuthorizingServiceClient::class, $mockedAuthorizingServiceInstance);
    }

    public function test_make_transaction_between_common_users()
    {
        /** @var User */
        $payer = User::factory()->create();
        $payee = User::factory()->create();

        $this->actingAs($payer);

        $this->postJson('/transactions', [
            'payee_id' => $payee->id,
            'value' => 100,
        ])
            ->assertNoContent();

        $payer->wallet->refresh();
        $payee->wallet->refresh();

        $this->assertEquals(8901, $payer->wallet->balance);
        $this->assertEquals(9101, $payee->wallet->balance);

        Bus::assertDispatched(NotifyTransactionJob::class);
    }

    public function test_make_transaction_from_common_user_to_shopkeeper()
    {
        /** @var User */
        $payer = User::factory()->create();
        $payee = User::factory()->shopkeeper()->create();

        $this->actingAs($payer);

        $this->postJson('/transactions', [
            'payee_id' => $payee->id,
            'value' => 100,
        ])
            ->assertNoContent();

        $payer->wallet->refresh();
        $payee->wallet->refresh();

        $this->assertEquals(8901, $payer->wallet->balance);
        $this->assertEquals(9101, $payee->wallet->balance);

        Bus::assertDispatched(NotifyTransactionJob::class);
    }

    public function test_try_to_make_transaction_as_shopkeeper_expects_403()
    {
        /** @var User */
        $payer = User::factory()->shopkeeper()->create();
        $payee = User::factory()->create();

        $this->actingAs($payer);

        $this->postJson('/transactions', [
            'payee_id' => $payee->id,
            'value' => 100,
        ])
            ->assertForbidden();

        $payer->wallet->refresh();
        $payee->wallet->refresh();

        $this->assertEquals(9001, $payer->wallet->balance);
        $this->assertEquals(9001, $payee->wallet->balance);

        Bus::assertNotDispatched(NotifyTransactionJob::class);
    }

    public function test_try_to_make_transaction_with_insufficient_balance_expects_422()
    {
        /** @var User */
        $payer = User::factory()->create();
        $payee = User::factory()->create();

        $this->actingAs($payer);

        $this->postJson('/transactions', [
            'payee_id' => $payee->id,
            'value' => 9002,
        ])
            ->assertUnprocessable([
                'errors' => [
                    'value' => ['The value exceeds current user balance.'],
                ],
            ]);

        $payer->wallet->refresh();
        $payee->wallet->refresh();

        $this->assertEquals(9001, $payer->wallet->balance);
        $this->assertEquals(9001, $payee->wallet->balance);

        Bus::assertNotDispatched(NotifyTransactionJob::class);
    }
}
