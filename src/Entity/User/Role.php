<?php

namespace App\Entity\User;

use Webmozart\Assert\Assert;

class Role
{
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    private $role;

    public function __construct($role)
    {
        Assert::oneOf($role, array_keys(self::getRoles()));

        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isRole(self $role): bool
    {
        return $this->getRole() === $role->getRole();
    }

    public static function user(): self
    {
        return new self(self::ROLE_USER);
    }

    public static function admin(): self
    {
        return new self(self::ROLE_ADMIN);
    }

    public static function getRoles(): array
    {
        return [
            self::ROLE_USER => 'user',
            self::ROLE_ADMIN => 'admin'
        ];
    }
}
