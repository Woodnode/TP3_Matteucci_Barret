<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class RegisterAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth('sanctum')->user()?->tokens()->count() > 0)
            throw new AuthorizationException('User already logged in.');

        else
            return true;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->input('password') !== $this->input('password_confirmation'))
        {
            throw new ValidationException('The password confirmation does not match.');
        }
        return [
            'login' => 'required|string|unique:users,login',
            'password' => 'required|string|min:8',
            'email' => 'required|string|email|unique:users,email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ];
    }
}
