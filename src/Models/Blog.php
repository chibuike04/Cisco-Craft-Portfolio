<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the blogs table. */
final class Blog extends Model
{
    protected string $table = 'blogs';

    protected array $fillable = [
        'category_id',
        'author_id',
        'featured_media_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'is_featured',
        'view_count',
        'published_at',
        'meta_title',
        'meta_description'
    ];

    protected bool $softDeletes = true;
}
