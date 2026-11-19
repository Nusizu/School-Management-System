<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->input('code'))),
        ]);
    }

    public function rules(): array
    {
        $subjectId = $this->route('subject')?->id;

        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('tbl_subjects', 'code')->ignore($subjectId)],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'Kode',
            'name' => 'Nama mata pelajaran',
            'description' => 'Keterangan',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute maksimal :max karakter.',
            'code.unique' => 'Kode sudah dipakai mata pelajaran lain (termasuk yang sudah dihapus).',
        ];
    }
}
