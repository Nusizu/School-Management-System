<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        // Role akun yang sedang dipakai tidak boleh diubah (mencegah admin mengunci dirinya sendiri)
        $target = $this->route('user');

        if ($target && $target->id === $this->user()?->id) {
            $this->merge(['role' => $target->role]);
        }
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('tbl_users', 'email')->ignore($userId)],
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_TEACHER, User::ROLE_STUDENT])],
            'password' => [$userId ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'role' => 'Role',
            'password' => 'Password',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'role.in' => 'Role tidak valid.',
            'password.min' => 'Password minimal :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
