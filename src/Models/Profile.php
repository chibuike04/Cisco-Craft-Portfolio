<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the profile table. */
final class Profile extends Model
{
    protected string $table = 'profile';

    protected array $fillable = [
        'full_name',
        'headline',
        'bio',
        'profile_image',
        'resume_file',
        'location',
        'public_email',
        'phone',
        'availability',
        'meta_title',
        'meta_description'
    ];
}
