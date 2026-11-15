<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the blog_categories table. */
final class BlogCategory extends Model
{
    protected string $table = 'blog_categories';

    protected array $fillable = [
        'name',
        'slug',
        'description'
    ];
}
