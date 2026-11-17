<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $classId = $this->route('class')?->id;

        return [
            'name' => [
                'required', 'string', 'max:50',
                Rule::unique('tbl_classes', 'name')->where('archived', 0)->ignore($classId),
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama kelas',
            'description' => 'Keterangan',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'name.unique' => 'Nama kelas sudah dipakai.',
        ];
    }
}
