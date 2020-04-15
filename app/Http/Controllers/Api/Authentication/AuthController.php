<?php /** @noinspection PhpUndefinedMethodInspection */

/** @noinspection PhpIncompatibleReturnTypeInspection */

namespace App\Http\Controllers\Api\Authentication;

use App\User;
use App\Image;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * Time taken in seconds before token expire.
     *
     * @var integer
     */
    public const TOKEN_EXPIRE = (60*60) * 12;

    /**
     * Register new user in the application.
     *
     * @param RegisterRequest $validator
     * @return JsonResponse
     */
    public function register(RegisterRequest $validator): JsonResponse
    {
        $token = auth()->login(
            $user = $this->createNewUser($validator->validated())
        );

        return $this->respondWithToken($token);
    }

    /**
     * Attempt to login the user and return a token.
     *
     * @param LoginRequest $validator
     * @return JsonResponse
     */
    public function login(LoginRequest $validator): JsonResponse
    {
        if(! $token = $this->attemptLogin($validator->validated())) {
            return response()->json(
                ['message' => 'Invalid credentials.'], JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        return $this->respondWithToken($token);
    }

    /**
     * Destroy current user token and get new token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(
            $token = auth()->refresh(true, true)
        );
    }

    /**
     * Get current user account record.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        $user = auth()->user();

        $user->image;

        return response()->json(auth()->user());
    }

    /**
     * Destroy current user token.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message', 'Have been loggedout successfully.']);
    }

    /**
     * Attempt to login the user.
     *
     * @param array $credentials
     * @return string
     */
    protected function attemptLogin(array $credentials): string
    {
        return auth()->setTTL(self::TOKEN_EXPIRE)->attempt($credentials);
    }

    /**
     * Respond with token and time token will expire in.
     *
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'token' => $token,
            'expire' => self::TOKEN_EXPIRE,
        ]);
    }

    /**
     * Create new user instance.
     *
     * @param array $data
     * @return User
     */
    protected function createNewUser(array $data): User
    {
        $user = User::create([
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'password' => Hash::make($data['password']),
        ]);

        $user->image = $this->createUserImage($user);

        return $user;
    }

    /**
     * Create user profile picture image.
     *
     * @param User $user
     * @return Image
     */
    protected function createUserImage(User $user): Image
    {
        return $user->image()->create([
            'path' => '',
            'url' => url(User::$defualtProfileImagePath)
        ]);
    }
}
