<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlockIfAuthenticated extends BlockIfUnauthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(! auth()->guest()) {
            return response()->json(
                ['messge' => 'Unauthorized Access.'], JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }

    /**
     * Check if user is not logged in.
     *
     * @return bool
     */
    public function isNotLoggedIn(): bool
    {
        return ! $this->isNotLoggedIn();
    }
}
