<?php /** @noinspection PhpUndefinedMethodInspection */

/** @noinspection PhpIncompatibleReturnTypeInspection */

namespace App\Http\Controllers\Api\Authentication;

use App\User;
use App\Logic\AuthenticationLogic;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * @var integer
     */
    public const EXPIRE_HALF_DAY = (60*60) * 12;

    /**
     * @var integer
     */
    public const EXPIRE_IN_YEAR = ((60*60)*24) * 365;

    /**
     * @var AuthenticationLogic
     */
    private $authentication;

    /**
     * AuthController constructor.
     *
     * @param AuthenticationLogic $authentication
     */
    public function __construct(AuthenticationLogic $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * Check if user is loggedin.
     *
     * @return JsonResponse
     */
    public function loggedin(): JsonResponse
    {
        return response()->json(['result' => auth()->check()]);
    }

    /**
     * Register new user in the application.
     *
     * @param RegisterRequest $validator
     * @return JsonResponse
     */
    public function register(RegisterRequest $validator): JsonResponse
    {
        return $this->respondWithToken(
            $this->authentication->register($validator->validated())
        );
    }

    /**
     * Attempt to login the user and return a token.
     *
     * @param LoginRequest $validator
     * @return JsonResponse
     */
    public function login(LoginRequest $validator): JsonResponse
    {
        if(! $user = $this->authentication->attemptLogin($validator->validated())) {
            return $this->invalidCredentials();
        }

        return $this->respondWithToken($user);
    }

    /**
     * Destroy current user token and get new token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        auth()->refresh(true, true);

        return $this->respondWithToken(auth()->user());
    }

    /**
     * Get current user account record.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(! ($user = auth()->user()) ?: $user->image);
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
     * Invalid credentials response.
     *
     * @return JsonResponse
     */
    protected function invalidCredentials(): JsonResponse {
        return response()->json(
            ['message' => 'Invalid email or password please try again.'], JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Respond with token and time token will expire in.
     *
     * @param User $user
     * @param bool $stayLoggedin
     * @return JsonResponse
     */
    protected function respondWithToken(User $user, bool $stayLoggedin = false): JsonResponse
    {
        return response()->json([
            'expire' => $expire = $stayLoggedin ? self::EXPIRE_HALF_DAY : self::EXPIRE_IN_YEAR,
            'token' => auth()->setTTL($expire)->login($user),
        ]);
    }
}
