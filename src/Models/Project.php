<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the projects table. */
final class Project extends Model
{
    protected string $table = 'projects';

    protected array $fillable = [
        'category_id',
        'thumbnail_media_id',
        'title',
        'slug',
        'summary',
        'description',
        'client_name',
        'role',
        'project_url',
        'repository_url',
        'status',
        'is_featured',
        'sort_order',
        'published_at',
        'meta_title',
        'meta_description'
    ];

    protected bool $softDeletes = true;
}
