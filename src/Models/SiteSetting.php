<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the site_settings table. */
final class SiteSetting extends Model
{
    protected string $table = 'site_settings';

    protected array $fillable = [
        'setting_key',
        'setting_value',
        'setting_group',
        'value_type'
    ];
}
