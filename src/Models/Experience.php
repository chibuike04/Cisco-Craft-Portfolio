<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the experiences table. */
final class Experience extends Model
{
    protected string $table = 'experiences';

    protected array $fillable = [
        'company',
        'position',
        'employment_type',
        'location',
        'description',
        'start_date',
        'end_date',
        'is_current',
        'sort_order'
    ];
}
