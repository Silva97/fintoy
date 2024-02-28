<?php

namespace App\Clients;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class NotificationClient
{
    private string $url = 'https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6';

    public function __construct(string $url = null)
    {
        if ($url) {
            $this->url = $url;
        }
    }

    /**
     * Notify user that received transaction.
     *
     * @return bool  True if notification has been send.
     */
    public function notifyTransaction(User $payee, int $value): bool
    {
        $moneyFormattedValue = number_format($value / 100, 2, ',', '.');

        $response = Http::post($this->url, [
            'name' => $payee->name,
            'email' => $payee->email,
            'message' => "Você recebeu uma transferência de R$ $moneyFormattedValue.",
        ]);

        return $response->status() == 200
            && $response->json('message') === 'true';
    }
}
