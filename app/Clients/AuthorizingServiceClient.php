<?php

namespace App\Clients;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class AuthorizingServiceClient
{
    private string $url = "https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc";

    public function __construct(string $url = null)
    {
        if ($url) {
            $this->url = $url;
        }
    }

    public function hasAuthorization(User $payer): bool
    {
        $response = Http::post($this->url, [
            'email' => $payer->email,
            'identification_number' => $payer->identification_number,
        ]);

        if ($response->status() != 200) {
            return false;
        }

        return $response->json('message') === 'Autorizado';
    }
}
