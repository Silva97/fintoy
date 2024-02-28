<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Wallet extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $visible = [
        'id',
        'balance',
    ];

    public function addBalance(int $value)
    {
        DB::table('wallets')
            ->where('id', $this->id)
            ->update([
                // Warning: DB:raw() could cause SQL injection.
                'balance' => DB::raw("balance + $value"),
            ]);
    }

    public function subtractBalance(int $value)
    {
        DB::table('wallets')
            ->where('id', $this->id)
            ->update([
                // Warning: DB:raw() could cause SQL injection.
                'balance' => DB::raw("balance - $value"),
            ]);
    }
}
