<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class MaxUserBalanceValue implements Rule
{
    protected int $currentBalance;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $currentBalance)
    {
        $this->currentBalance = $currentBalance;
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
        return is_int($value)
            && $value <= $this->currentBalance;
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
