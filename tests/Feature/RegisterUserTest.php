<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Testing\TestResponse;
use Faker\Factory as Faker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * Try to register with valid data.
     */
    public function testRegisterWithVaildData()
    {
        $this->register($this->formData())->assertOk();
    }

    /**
     * Try to register with exist email address.
     */
    public function testRegisterWithExistEmailAddress()
    {
        $user = factory(User::class)->create();
        $data = array_merge($this->formData(), ['email' => $user->email]);

        $this->register($data)
             ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJsonValidationErrors(['email']);
    }

    /**
     * Try to register as authenticated user.
     */
    public function testRegisterAsAuthenticatedUser()
    {
        $token = auth()->login(factory(User::class)->create());
        $data = $this->formData();

        $this->register($data, $token)
             ->assertUnauthorized();
    }

    /**
     * Try to register empty empty data.
     */
    public function testRegisterWithEmptyData()
    {
        $this->register([])
             ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJsonValidationErrors(['first_name', 'last_name', 'email', 'password']);
    }

    /**
     * Try to register with empty first name.
     */
    public function testRegisterWithEmptyFirstName()
    {
        $this->register($this->formData('first_name'))
             ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJsonValidationErrors(['first_name']);
    }

    /**
     * Try to register with empty first last name.
     */
    public function testRegisterWithEmptyLastName()
    {
        $this->register($this->formData('last_name'))
             ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJsonValidationErrors(['last_name']);
    }

    /**
     * Try to register with empty email address
     */
    public function testRegisterWithEmptyEmail()
    {
        $this->register($this->formData('email'))
             ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJsonValidationErrors('email');
    }

    /**
     * Try to register with invalid email address.
     */
    public function testRegisterWithInvalidEmail()
    {
        $data = $this->formData();
        $data['email'] = str_replace('@', '', $data['email']);

        $this->register($data)->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJsonValidationErrors('email');
    }

    /**
     * Try to register with empty password.
     */
    public function testRegiterWithEmptyPassword()
    {
        $this->register($this->formData('password'))
             ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJsonValidationErrors('password');
    }

    /**
     * Try to register with empty password confirmation.
     */
    public function testRegisterWithEmptyPasswordConfirmation()
    {
        $this->register($this->formData($key = 'password_confirmation'))
             ->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJsonValidationErrors('password');
    }

    /**
     * Try to register with password confirmation that does not match password.
     */
    public function testRegisterWithIncorrectPasswordConfirmation()
    {
        $data = $this->formData();

        $data['password_confirmation'] = $data['password_confirmation'] . '&^';

        $this->register($data)->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Generate new register form data using faker.
     *
     * @param string|null $remove
     * @return array
     */
    protected function formData(string $remove = null): array
    {
        $form = [
            'first_name' =>  ($faker = Faker::create())->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->email,
            'password' => $password =  $faker->password(8,20),
            'password_confirmation' => $password
        ];

        return is_null($remove) ? $form : array_merge($form, [strtolower($remove) => '']);
    }

    /**
     * Makes register request to application.
     *
     * @param array $data
     * @param string $token
     * @return TestResponse
     */
    protected function register(array $data, string $token = ''): TestResponse
    {
        return $this->withHeader('Authorization', $token)
            ->json('POST', 'api/authentication/register', $data);
    }
}
