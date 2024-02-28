<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\MaxUserBalanceValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionCreateRequest extends FormRequest
{
    const UINT32_MAX = 4294967295; // pow(2, 32) - 1

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !$this->user()->is_shopkeeper;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payee_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists(User::class, 'id'),
            ],
            'value' => [
                'required',
                'integer',
                'min:1',
                // In 32-bit systems PHP_INT_MAX is less than self::UINT32_MAX.
                // So to avoid integer overflow on PHP context we should
                // ensure to not pass the PHP limit.
                'max:' . min(self::UINT32_MAX, PHP_INT_MAX),
                new MaxUserBalanceValue(),
            ],
        ];
    }
}
