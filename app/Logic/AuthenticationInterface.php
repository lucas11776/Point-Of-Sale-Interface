<?php

namespace App\Logic;

use App\User;
use Illuminate\Support\Collection;

interface AuthenticationInterface
{
    /**
     * Check if user is loggedin to the application.
     *
     * @return bool
     */
    public function loggedin(): bool;

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
    public function attemptLogin(array $credentials);

    /**
     * Check if user roles exists.
     *
     * @param User $user
     * @param string $name
     * @return bool
     */
    public function roleExists(User $user, string $name): bool;

    /**
     * Check if user is administor
     *
     * @param User $user
     * @return bool
     */
    public function isAdministrator(User $user): bool;

    /**
     * Logout user or destroy token.
     *
     * @return bool
     */
    public function logout(): bool;
}
