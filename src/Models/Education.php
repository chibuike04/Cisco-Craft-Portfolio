<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the education table. */
final class Education extends Model
{
    protected string $table = 'education';

    protected array $fillable = [
        'institution',
        'qualification',
        'field_of_study',
        'description',
        'start_date',
        'end_date',
        'sort_order'
    ];
}
