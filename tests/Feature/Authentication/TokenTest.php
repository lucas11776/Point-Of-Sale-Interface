<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\TestResponse;

class TokenTest extends TestCase
{
   /**
    * Try to refresh token as authenticated user.
    */
   public function testRefreshTokenAsAuthenticated()
   {
       $token = auth()->login($this->getUser());

       $this->refreshToken($token)
           ->assertOk();
   }

   /**
    * Try to refresh token as unauthenticated user.
    */
   public function testRefreshTokenAsUnauthenticated()
   {
        $this->refreshToken()
            ->assertUnauthorized();
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
