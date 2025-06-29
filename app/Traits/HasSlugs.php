<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlugs
{
    public static function bootHasSlugs(): void
    {
        static::creating(function ($model) {
            $model->slug = self::uniqueSlug($model);
        });

        static::updating(function ($model) {
            $model->slug = self::uniqueSlug($model);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    private static function uniqueSlug($model): string
    {
        $slug = Str::slug($model->name);

        $existingSlugs = $model::where('slug', 'LIKE', "$slug%")
            ->when($model->exists, fn($q) => $q->where('id', '!=', $model->id))
            ->pluck('slug');

        if ($existingSlugs->isEmpty()) {
            return $slug;
        }

        return $slug . '-' . ($existingSlugs->count() + 1);
    }
}
