<?php

namespace Tests\Feature\Authentication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    /**
     * Check if employee make request result are true.
     */
    public function testResultAreTrueIfUserRoleExist()
    {
        auth()->login($this->getEmployee());

        $this->userRole('employee')
            ->assertJson(['result' => true]);
    }

    /**
     * Check if result are false if user request role that deos not belong to user.
     */
    public function testResultAreFalseIfUserRoleDoesNotExist()
    {
        auth()->login($this->getEmployee());

        $this->userRole('administrator')
            ->assertJson(['result' => false]);
    }

    /**
     * Check if role that deos not exist return result false.
     */
    public function testIfRoleDoesNotExistReturenPageNotFound()
    {
        auth()->login($this->getAdministrator());

        $this->userRole('super-administrator')
            ->assertStatus(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * Check if user role exist.
     *
     * @param string $name
     * @return TestResponse
     */
    protected function userRole(string $name): TestResponse
    {
        return $this->get('api/user/role/' . $name);
    }
}
