<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $studentId = $this->route('student')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'class_id' => ['required', 'exists:tbl_classes,id'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:active,transferred'],
            'user_id' => ['nullable', 'exists:tbl_users,id', Rule::unique('tbl_students', 'user_id')->ignore($studentId)],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'class_id' => 'Kelas',
            'birth_date' => 'Tanggal lahir',
            'phone' => 'Telepon',
            'address' => 'Alamat',
            'status' => 'Status',
            'user_id' => 'Akun pengguna',
            'photo' => 'Foto',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'birth_date.before' => 'Tanggal lahir tidak valid.',
            'user_id.unique' => 'Akun tersebut sudah dipakai siswa lain.',
            'photo.image' => 'Foto harus berupa gambar.',
            'photo.mimes' => 'Foto harus berformat JPG atau PNG.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}