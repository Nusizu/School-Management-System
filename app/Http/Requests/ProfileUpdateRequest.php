<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:100',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.lowercase' => 'Email harus menggunakan huruf kecil.',
            'email.unique' => 'Email sudah dipakai akun lain.',
        ];
    }
}
