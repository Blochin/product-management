<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait HasLogs
{
    public static function bootHasLogs(): void
    {
        static::created(function ($model) {
            Log::info('Model Created', [
                'model' => class_basename($model),
                'id' => $model->getKey(),
                'attributes' => $model->getAttributes(),
                'user_id' => auth()->id()
            ]);
        });

        static::updated(function ($model) {
            Log::info('Model Updated', [
                'model' => class_basename($model),
                'id' => $model->getKey(),
                'changes' => $model->getChanges(),
                'original' => $model->getOriginal(),
                'user_id' => auth()->id()
            ]);
        });

        static::deleted(function ($model) {
            Log::info('Model Deleted', [
                'model' => class_basename($model),
                'id' => $model->getKey(),
                'attributes' => $model->getAttributes(),
                'user_id' => auth()->id()
            ]);
        });
    }
}
