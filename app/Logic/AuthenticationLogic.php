<?php

namespace App\Logic;

use App\User;

class AuthenticationLogic extends UserLogic implements AuthenticationInterface
{
    /**
     * @inheritDoc
     */
    public function loggedin(): bool
    {
        return auth()->check();
    }

    /**
     * @inheritDoc
     */
    public function register(array $form): User
    {
        return $this->create($form);
    }

    /**
     * @inheritDoc
     */
    public function attemptLogin(array $credentials)
    {
        return auth()->validate($credentials) ? User::where('email', $credentials['email'])->first() : null;
    }

    /**
     * @inheritDoc
     */
    public function roleExists(User $user, string $name): bool
    {
        return $this->isAdministrator($user) ? true : $this->hasRole($user, $name);
    }

    /**
     * @inheritDoc
     */
    public function logout(): bool
    {
        // TODO: Implement logout() method.
    }

    /**
     * Check if user is administrator.
     *
     * @param User $user
     * @return bool
     */
    public function isAdministrator(User $user): bool
    {
        return $this->hasRole($user, 'administrator');
    }
}
