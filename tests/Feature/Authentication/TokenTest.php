<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\TestResponse;
use Faker\Factory;
use Tests\TestCase;

class TokenTest extends TestCase
{
   /**
    * Try to refresh token as authenticated user.
    */
   public function testRefreshTokenAsAuthenticated()
   {
       $this->refreshToken($this->getToken())->assertOk();
   }

   /**
    * Try to refresh token as unauthenticated user.
    */
   public function testRefreshTokenAsUnauthenticated()
   {
        $this->refreshToken()->assertUnauthorized();
   }

    /**
     * @return string
     */
   protected function getToken(): string
   {
        return auth()->login(factory(User::class)->create());
   }

    /**
     * Make refresh token request to application.
     *
     * @param string $token
     * @return TestResponse
     */
   protected function refreshToken(string $token = ''): TestResponse
   {
        return $this->withHeader('authorization', $token)
            ->post('api/authentication/refresh');
   }
}
