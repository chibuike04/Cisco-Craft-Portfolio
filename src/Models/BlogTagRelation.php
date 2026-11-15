<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the blog_tag_relations table. */
final class BlogTagRelation extends Model
{
    protected string $table = 'blog_tag_relations';

    protected array $fillable = [
        'blog_id',
        'tag_id'
    ];
}
