<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Concerns\HasSlug;

class SchoolClass extends Model
{
    use HasSlug;

    protected $table = 'tbl_classes';

    protected $fillable = ['name', 'description', 'archived'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function scopeActive($query)
    {
        return $query->where('archived', 0);
    }
}