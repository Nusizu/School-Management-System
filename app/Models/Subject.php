<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\HasSlug;

class Subject extends Model
{
    use HasSlug;

    protected $table = 'tbl_subjects';

    protected $fillable = ['code', 'name', 'description', 'archived'];

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class, 'subject_id');
    }

    public function scopeActive($query)
    {
        return $query->where('tbl_subjects.archived', 0);
    }
}