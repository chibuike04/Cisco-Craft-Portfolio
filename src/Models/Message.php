<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the messages table. */
final class Message extends Model
{
    protected string $table = 'messages';

    protected array $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'ip_address',
        'user_agent',
        'read_at'
    ];
}
