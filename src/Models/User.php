<?php

declare(strict_types=1);

namespace App\Models;

/** Database model for the users table. */
final class User extends Model
{
    protected string $table = 'users';

    protected array $fillable = [
        'role_id',
        'name',
        'email',
        'password_hash',
        'status',
        'last_login_at'
    ];

    /**
     * Find a user by email address.
     */
    public function findByEmail(string $email): ?array
    {
        return $this->firstWhere(
            'email',
            strtolower(trim($email))
        );
    }

    /**
     * Check whether an email address is already registered.
     */
    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    /**
     * Update the user's last login timestamp.
     */
    public function updateLastLogin(int $userId): bool
    {
        return $this->update($userId, [
            'last_login_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Update the user's password hash.
     */
    public function updatePasswordHash(
        int $userId,
        string $passwordHash
    ): bool {
        return $this->update($userId, [
            'password_hash' => $passwordHash
        ]);
    }
}
