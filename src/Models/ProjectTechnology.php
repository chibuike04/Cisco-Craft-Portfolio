<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the project_technology table. */
final class ProjectTechnology extends Model
{
    protected string $table = 'project_technology';

    protected array $fillable = [
        'project_id',
        'technology_id'
    ];
}
