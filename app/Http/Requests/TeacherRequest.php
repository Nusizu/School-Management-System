<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $teacherId = $this->route('teacher')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'subject_id' => ['required', Rule::exists('tbl_subjects', 'id')->where('archived', 0)],
            'employee_number' => ['nullable', 'string', 'max:30', Rule::unique('tbl_teachers', 'employee_number')->ignore($teacherId)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'user_id' => [
                'nullable',
                Rule::exists('tbl_users', 'id')->where('role', 'teacher')->where('archived', 0),
                Rule::unique('tbl_teachers', 'user_id')->ignore($teacherId),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'subject_id' => 'Mata pelajaran',
            'employee_number' => 'Nomor induk',
            'phone' => 'Telepon',
            'address' => 'Alamat',
            'user_id' => 'Akun pengguna',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'employee_number.unique' => 'Nomor induk sudah dipakai guru lain.',
            'subject_id.exists' => 'Mata pelajaran tidak valid.',
            'user_id.exists' => 'Akun pengguna tidak valid.',
            'user_id.unique' => 'Akun tersebut sudah dipakai guru lain.',
        ];
    }
}
