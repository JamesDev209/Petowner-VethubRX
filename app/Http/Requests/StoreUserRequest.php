<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'adress' => ['required', 'string'], 
            'phone' => [
                'required',
                'regex:/^[0-9]{10,15}$/',
            ],
            'email' => ['required', 'email', 'unique:users,email'],
        ];
    }
}
