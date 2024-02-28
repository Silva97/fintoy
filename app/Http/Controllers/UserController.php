<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function create(UserCreateRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $userData = DB::transaction(function () use ($validatedData) {
            $wallet = new Wallet();
            $wallet->balance = 0;
            $wallet->save();

            $user = new User();
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->is_shopkeeper = $validatedData['is_shopkeeper'];
            $user->identification_number = $validatedData['identification_number'];
            $user->wallet_id = $wallet->id;
            $user->setPassword($validatedData['password']);
            $user->save();

            return $user->toArray();
        });

        return response()->json($userData, JsonResponse::HTTP_CREATED);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !$user->checkPassword($validatedData['password'])) {
            return response()->json([], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = auth()->login($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ]);
    }
}
