<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $visible = [
        'id',
        'payer_wallet_id',
        'payee_wallet_id',
        'value',
        'transferred_at',
    ];

    protected $casts = [
        'transferred_at' => 'datetime',
    ];
}
