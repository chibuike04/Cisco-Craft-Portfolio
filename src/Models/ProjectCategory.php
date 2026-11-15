<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the project_categories table. */
final class ProjectCategory extends Model
{
    protected string $table = 'project_categories';

    protected array $fillable = [
        'name',
        'slug',
        'description',
        'sort_order'
    ];
}
