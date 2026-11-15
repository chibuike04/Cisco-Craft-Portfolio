<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the skills table. */
final class Skill extends Model
{
    protected string $table = 'skills';

    protected array $fillable = [
        'name',
        'category',
        'proficiency',
        'icon',
        'sort_order',
        'is_visible'
    ];
}
