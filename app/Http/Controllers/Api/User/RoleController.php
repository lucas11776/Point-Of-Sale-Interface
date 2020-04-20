<?php /** @noinspection ALL */

namespace App\Http\Controllers\Api\User;

use App\User;
use App\Role;
use App\UsersRoles;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddRoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Get user roles.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(auth()->user()->roles);
    }

    /**
     * Add role to a user.
     *
     * @param AddRoleRequest $validator
     * @return JsonResponse
     */
    public function add(int $userId, AddRoleRequest $validator): JsonResponse
    {
        if($this->hasRole($userId, $role = \request('role'))) {
            return response()->json(
                ['message' => 'User role has been added.'], JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $this->addRolePivot($userId, $role);

        return response()->json(['message' => 'User role has been added.']);
    }

    /**
     * Check if user has role.
     *
     * @param int $userId
     * @param string $role
     */
    protected function hasRole(int $userId, string $role): bool
    {
        return User::where('id', $userId)
            ->first()->roles()->where('name', $role)->first() ? true : false;
    }

    /**
     * Add user role relationship (pivot).
     *
     * @param int $userId
     * @param string $role
     * @return UsersRoles
     */
    protected function addRolePivot(int $userId, string $role): UsersRoles
    {
        return User::where('id', $userId)->first()->addRole(
            $role = Role::where('name', $role)->first()
        );
    }
}
