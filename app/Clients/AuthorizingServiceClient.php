<?php

namespace App\Clients;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class AuthorizingServiceClient
{
    private string $url = 'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc';

    public function __construct(string $url = null)
    {
        if ($url) {
            $this->url = $url;
        }
    }

    /**
     * Check if user is authorized to do a transaction.
     *
     * @return bool  True if user has authorization.
     */
    public function hasAuthorization(User $payer): bool
    {
        $response = Http::post($this->url, [
            'email' => $payer->email,
            'identification_number' => $payer->identification_number,
        ]);

        return $response->status() == 200
            && $response->json('message') === 'Autorizado';
    }
}
