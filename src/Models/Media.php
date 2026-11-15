<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the media table. */
final class Media extends Model
{
    protected string $table = 'media';

    protected array $fillable = [
        'uploaded_by',
        'disk',
        'file_name',
        'original_name',
        'file_path',
        'mime_type',
        'size_bytes',
        'alt_text'
    ];
}
