<?php

namespace App\Http\Middleware;

use App\Logic\AuthenticationLogic;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlockIfUnauthenticated
{
    /**
     * @var AuthenticationLogic
     */
    protected $authentication;

    /**
     * BlockIfUnauthenticated constructor.
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
        if (! $this->authentication->loggedin()) {
            return response()->json(
                ['messge' => 'Unauthorized Access.'], JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }
}
