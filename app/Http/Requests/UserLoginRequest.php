<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email', // email kerakli, to'g'ri formatda va userlar jadvalidan olinishi kerak
            'password' => 'required|string|min:8', // password kerakli, minimal uzunligi 8 ta belgidan iborat bo'lishi kerak
        ];
    }
}



