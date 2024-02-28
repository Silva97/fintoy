<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionCreateRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function create(TransactionCreateRequest $request): Response
    {
        $validatedData = $request->validated();
        $payer = auth()->user();
        $payee = User::find($validatedData['payee_id']);
        if (!$payee) {
            return response('', Response::HTTP_FORBIDDEN);
        }

        try {
            DB::transaction(function () use ($validatedData, $payee, $payer) {
                $transaction = new Transaction();
                $transaction->payer_wallet_id = $payer->wallet_id;
                $transaction->payee_wallet_id = $payee->wallet_id;
                $transaction->value = $validatedData['value'];
                $transaction->transferred_at = Carbon::now();
                $transaction->save();

                $payer->wallet->subtractBalance($transaction->value);
                $payee->wallet->addBalance($transaction->value);
            });
        } catch (\Throwable $e) {
            return response('', Response::HTTP_FORBIDDEN);
        }

        return response()->noContent();
    }
}
