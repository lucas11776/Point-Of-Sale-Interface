<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AllowIfEmployee extends AllowIfAdministrator
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
        if(! $this->isLoggedIn() || ! $this->isEmployee()) {
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
        return $this->isAdministrator() OR auth()->user()->roles('name', 'employee')->first();
    }
}
