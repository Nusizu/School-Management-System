<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Slug otomatis dari kolom `name` dengan format "nama-orang-12" (nama + id).
 * Dipakai sebagai route key, sehingga URL menjadi /students/budi-santoso-1/edit.
 */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        // Setelah data dibuat, id sudah ada, jadi slug bisa dibentuk
        static::created(function ($model) {
            $slug = $model->makeSlug();

            DB::table($model->getTable())
                ->where($model->getKeyName(), $model->getKey())
                ->update(['slug' => $slug]);

            $model->setAttribute('slug', $slug);
            $model->syncOriginalAttribute('slug');
        });

        // Nama berubah, slug ikut diperbarui
        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->slug = $model->makeSlug();
            }
        });
    }

    public function makeSlug(): string
    {
        return (Str::slug((string) $this->name) ?: 'item').'-'.$this->getKey();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
