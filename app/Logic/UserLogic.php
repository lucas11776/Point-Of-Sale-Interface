<?php

namespace App\Logic;

use App\Role;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserLogic implements UserInterface
{
    /**
     * @inheritDoc
     */
    public function uploadProfilePicture(User $user, UploadedFile $image): bool
    {
        $path = Storage::put('public', $image);

        return $this->updateUserImage($user, $path);
    }

    /**
     * @inheritDoc
     */
    public function changePassword(User $user, string $password): bool
    {
        return $user->update(['password' => Hash::make($password)]);
    }

    /**
     * @inheritDoc
     */
    public function hasRole(User $user, string $name): bool
    {
        return $user->roles()->where('name', strtolower($name))->first() ? true : false;
    }

    /**
     * @inheritDoc
     */
    public function addRole(User $user, string $name): void
    {
        $user->roles()->attach($this->getRole($name)->id);
    }

    /**
     * @inheritDoc
     */
    public function getRole(string $name): Role
    {
        return Role::where(['name' => $name])->first();
    }

    /**
     * Create new user account in storage.
     *
     * @param array $form
     * @return User
     */
    protected function create(array $form): User
    {
        $user = User::create([
            'email' => $form['email'],
            'last_name' => $form['last_name'],
            'first_name' => $form['first_name'],
            'password' => Hash::make($form['password']),
        ]);

        $this->createImage($user);

        return $user;
    }

    /**
     * Create defualt user account image.
     *
     * @param User $user
     * @return Model
     */
    private function createImage(User $user): Model
    {
        return $user->image()->create([
            'path' => '',
            'url' => url(User::$defualtProfileImagePath)
        ]);
    }

    /**
     * Update user profile image.
     *
     * @param User $user
     * @param string $path
     * @return bool
     */
    protected function updateUserImage(User $user, string $path): bool
    {
        Storage::delete($user->image->path);

        return $user->image->update(['path' => $path, 'url' => url($path)]);
    }
}
