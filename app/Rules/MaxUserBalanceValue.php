<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxUserBalanceValue implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_int($value)) {
            return false;
        }

        $userWallet = optional(auth()->user())->wallet;
        if (!$userWallet) {
            return false;
        }

        return $value <= $userWallet->balance;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The value exceeds current user balance.';
    }
}
