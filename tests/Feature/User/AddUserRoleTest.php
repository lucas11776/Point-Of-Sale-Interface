<?php /** @noinspection ALL */

namespace Tests\Feature\User;

use App\Role;
use App\User;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\TestResponse;

class AddUserRoleTest extends TestCase
{
    /**
     * Try to make a user to an administrator.
     */
    public function testAddUserRoleAdministrator()
    {
        auth()->login($this->getAdministrator());

        $user = $this->getUser();
        $data = ['role' => 'administrator'];

        $this->addUserRole($user->id, $data)
            ->assertOk();

        $this->assertRolePivotExits($user, 'administrator');
    }

    /**
     * Try to make a user to an employee.
     */
    public function testAddUserRoleEmployee()
    {
        auth()->login($this->getAdministrator());

        $user = $this->getUser();
        $data = ['role' => 'employee'];

        $this->addUserRole($user->id, $data)
            ->assertOk();

        $this->assertRolePivotExits($user, 'employee');
    }

    /**
     * Try to add role that already exist in user.
     */
    /**
     * Try to add a user role with empty role.
     */
    public function testAddExistingUserRole()
    {
        auth()->login($user = $this->getAdministrator());

        $data = ['role' => 'administrator'];

        $this->addUserRole($user->id, $data)
            ->assertStatus(JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * Try to add a user role with empty role.
     */
    public function testAddUserRoleWithEmptyRole()
    {
        auth()->login($this->getAdministrator());

        $user = $this->getUser();
        $data = [];

        $this->addUserRole($user->id, $data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Try to add user role that does to exist in storage.
     */
    public function testAddInvalidUserRole()
    {
        auth()->login($this->getAdministrator());

        $user = $this->getUser();
        $data = ['role' => 'super-administrator'];

        $this->addUserRole($user->id, $data)
            ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Try to make a user to employee as unauthorized user.
     */
    public function testAddUserRoleEmployeeAsUnauthenticatedUser()
    {
        $user = $this->getUser();
        $data = ['role' => 'employee'];

        $this->addUserRole($user->id, $data)
            ->assertUnauthorized();
    }

    /**
     * Check if user role relation ship exist.
     *
     * @param User $user
     */
    public function assertRolePivotExits(User $user, string $role)
    {
        $this->assertTrue(
            $user->roles()->where('name', $role)->first() ? true : false,
            'User role relationship of ' . $role . ' deos not exist'
        );
    }

    /**
     * Make a request to add user role to application.
     *
     * @param int $userId
     * @param array $data
     * @param string $token
     * @return TestResponse
     */
    protected function addUserRole(int $userId, array $data, string $token = ''): TestResponse
    {
        return $this->withHeader('Authorization', $token)
            ->json('POST', 'api/user/' . $userId . '/add/role', $data);
    }
}
