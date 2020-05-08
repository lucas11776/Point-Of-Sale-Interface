<?php

namespace App\Logic;

use App\User;
use Illuminate\Support\Collection;

interface AuthenticationInterface
{
    /**
     * Create new user account in storage.
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User;

    /**
     * Attempt to login the user using credentils.
     *
     * @param array $credentials
     * @return User
     */
    public function attemptLogin(array $credentials): User;

    /**
     * Check if user roles exists.
     *
     * @param User $user
     * @param string $name
     * @return bool
     */
    public function roleExists(User $user, string $name): bool;

    /**
     * Logout user or destroy token.
     *
     * @return bool
     */
    public function logout(): bool;
}
