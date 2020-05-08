<?php

namespace App\Logic;

use App\Image;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AuthenticationLogic implements AuthenticationInterface
{
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
    public function attemptLogin(array $credentials): User
    {
        return auth()->validate($credentials) ? User::where('email', $credentials['email'])->first() : null;
    }

    /**
     * @inheritDoc
     */
    public function roleExists(User $user, string $name): bool
    {
        return $user->roles()->where('name', strtolower($name))->first() ? true : false;
    }

    /**
     * @inheritDoc
     */
    public function logout(): bool
    {
        // TODO: Implement logout() method.
    }

    /**
     * Create new user account in storage.
     *
     * @param array $form
     * @return User
     */
    protected function create(array $form): User {
        $user = User::create([
            'email' => $form['email'],
            'last_name' => $form['last_name'],
            'first_name' => $form['first_name'],
            'password' => Hash::make($form['password']),
        ]);

        $this->createImage($user);

        return $user;
    }

    private function createImage(User $user): Model
    {
        return $user->image()->create([
            'path' => '',
            'url' => url(User::$defualtProfileImagePath)
        ]);
    }
}
