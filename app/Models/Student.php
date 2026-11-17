<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\HasSlug;

class Student extends Model
{
    use HasSlug;

    protected $table = 'tbl_students';

    protected $fillable = [
        'user_id', 'class_id', 'student_code', 'name', 'birth_date',
        'address', 'phone', 'img_url', 'status', 'archived',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'archived' => 'boolean',
        ];
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('tbl_students.archived', 0);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'active' ? 'Aktif' : 'Pindah';
    }

    public function getBirthDateFormattedAttribute(): string
    {
        return $this->birth_date ? $this->birth_date->translatedFormat('j M Y') : '-';
    }
}