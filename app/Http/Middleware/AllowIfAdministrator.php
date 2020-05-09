<?php /** @noinspection ALL */

namespace App\Http\Middleware;

use App\Logic\AuthenticationLogic;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AllowIfAdministrator
{
    /**
     * @var AuthenticationLogic
     */
    protected $authentication;

    /**
     * AllowIfAdministrator constructor.
     *
     * @param AuthenticationLogic $authentication
     */
    public function __construct(AuthenticationLogic $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * Handle an incoming request.
     *s
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(! $this->isAdministrator()) {
            return response()->json(
                ['message' => 'Unauthorized Access'], JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }

    /**
     * Check if user is administrator and loggedin.
     *
     * @return bool
     */
    protected function isAdministrator(): bool
    {
        return $this->authentication->loggedin() AND $this->authentication->isAdministrator(auth()->user());
    }
}
