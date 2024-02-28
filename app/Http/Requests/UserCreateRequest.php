<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:320',
                Rule::unique(User::class),
            ],
            'is_shopkeeper' => [
                'required',
                'boolean',
            ],
            'identification_number' => [
                'required',
                'string',
                'regex:/^[0-9]+$/',
                ($this->is_shopkeeper === true)
                    ? 'size:14'
                    : 'size:11',
                Rule::unique(User::class),
            ],
            'password' => [
                'required',
                'string',
                'min:14',
                'max:72',
            ],
        ];
    }
}
