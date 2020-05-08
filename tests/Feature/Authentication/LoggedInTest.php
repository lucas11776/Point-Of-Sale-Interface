<?php

namespace Tests\Feature\Authentication;

use Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;

class LoggedInTest extends TestCase
{
    /**
     * Check if result are true is user is loggedin.
     */
    public function testResultAreTrueIfUserIsLoggedIn()
    {
        auth()->login($this->getUser());

        $this->loggedin()
            ->assertJson(['result' => true]);
    }

    /**
     * Check if result are false if user is not logged in.
     */
    public function testResultAreFalseIfUserIsNotLoggedIn()
    {
        $this->loggedin()
            ->assertJson(['result' => false]);
    }

    /**
     * Make request to check if user is loggedin application.
     *
     * @return TestResponse
     */
    protected function loggedin(): TestResponse
    {
        return $this->get('api/authentication/loggedin');
    }
}
