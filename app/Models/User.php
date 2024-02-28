<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $visible = [
        'id',
        'email',
        'name',
        'identification_number',
        'is_shopkeeper',
    ];

    protected $casts = [
        'is_shopkeeper' => 'bool',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Check if the given password is the same of the current user's password.
     *
     * @param string $password  The password to check.
     *
     * @return bool Returns true if the passwords match.
     */
    public function checkPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Change the user's password to the given password. The password
     * will be hashed then stored.
     *
     * @param string $password  The password to user.
     *
     * @return void
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
