<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the blog_tags table. */
final class BlogTag extends Model
{
    protected string $table = 'blog_tags';

    protected array $fillable = [
        'name',
        'slug'
    ];
}
