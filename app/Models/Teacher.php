<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    protected $table = 'tbl_teachers';

    protected $fillable = [
        'user_id', 'subject_id', 'employee_number', 'name', 'phone', 'address', 'archived',
    ];

    protected function casts(): array
    {
        return ['archived' => 'boolean'];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('tbl_teachers.archived', 0);
    }
}