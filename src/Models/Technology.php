<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the technologies table. */
final class Technology extends Model
{
    protected string $table = 'technologies';

    protected array $fillable = [
        'name',
        'slug'
    ];
}
