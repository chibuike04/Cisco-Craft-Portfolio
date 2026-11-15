<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the social_links table. */
final class SocialLink extends Model
{
    protected string $table = 'social_links';

    protected array $fillable = [
        'platform',
        'label',
        'url',
        'icon',
        'sort_order',
        'is_visible'
    ];
}
