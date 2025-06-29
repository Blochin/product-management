<?php

namespace App\Models;

use App\Traits\HasLogs;
use App\Traits\HasSlugs;
use App\Traits\HasToggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use HasSlugs;
    use HasLogs;
    use SoftDeletes;
    use HasToggle;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'product_number',
        'price',
        'description',
        'category_id',
        'is_active',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
