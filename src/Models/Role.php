<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the roles table. */
final class Role extends Model
{
    protected string $table = 'roles';

    protected array $fillable = [
        'name',
        'slug',
        'description'
    ];
}
