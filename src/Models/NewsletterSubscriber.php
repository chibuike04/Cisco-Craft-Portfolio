<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the newsletter_subscribers table. */
final class NewsletterSubscriber extends Model
{
    protected string $table = 'newsletter_subscribers';

    protected array $fillable = [
        'email',
        'name',
        'status',
        'subscribed_at',
        'confirmed_at',
        'unsubscribed_at'
    ];
}
