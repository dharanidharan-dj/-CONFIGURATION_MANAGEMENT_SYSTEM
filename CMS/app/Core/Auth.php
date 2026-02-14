<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

final class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['auth_user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['auth_user'] ?? null;
    }

    public static function attempt(string $username, string $password): bool
    {
        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        $_SESSION['auth_user'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
        ];

        session_regenerate_id(true);
        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['auth_user']);
        session_regenerate_id(true);
    }
}
