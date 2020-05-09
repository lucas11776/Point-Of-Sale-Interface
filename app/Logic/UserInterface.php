<?php

namespace App\Logic;

use App\Role;
use App\User;
use Illuminate\Http\UploadedFile;

interface UserInterface
{
    /**
     * Change user profile picture in storage.
     *
     * @param User $user
     * @param UploadedFile $image
     * @return bool
     */
    public function uploadProfilePicture(User $user, UploadedFile $image): bool;

    /**
     * Chaneg user pasword in storage.
     *
     * @param User $user
     * @param string $password
     * @return bool
     */
    public function changePassword(User $user, string $password): bool;

    /**
     * Check if user has role.
     *
     * @param User $user
     * @param string $name
     * @return bool
     */
    public function hasRole(User $user, string $name): bool;

    /**
     * Add user role to the user.
     *
     * @param User $user
     * @param string $name
     * @return void
     */
    public function addRole(User $user, string $name): void;

    /**
     * Get role.
     *
     * @param string $name
     * @return Role
     */
    public function getRole(string $name): Role;
}
