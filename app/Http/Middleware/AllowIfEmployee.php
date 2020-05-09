<?php

namespace App\Http\Middleware;

use App\Logic\AuthenticationLogic;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AllowIfEmployee
{
    /**
     * @var AuthenticationLogic
     */
    protected $authentication;

    /**
     * AllowIfEmployee constructor.
     *
     * @param AuthenticationLogic $authentication
     */
    public function __construct(AuthenticationLogic $authentication)
    {
        $this->authentication = $authentication;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(! $this->isEmployee()) {
            return response()->json(
                ['Unauthorized Access.'], JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }

    /**
     * Check if user is employee or administrator.
     *
     * @return bool
     */
    protected function isEmployee(): bool
    {
        return $this->authentication->loggedin() AND $this->authentication->roleExists(auth()->user(), 'employee');
    }
}
