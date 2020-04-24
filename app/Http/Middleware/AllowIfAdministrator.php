<?php /** @noinspection ALL */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AllowIfAdministrator extends BlockIfUnauthenticated
{
    /**
     * Handle an incoming request.
     *s
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(! $this->isLoggedIn() ||  ! $this->isAdministrator()) {
            return response()->json(
                ['Unauthorized Access.'], JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }

    /**
     * Check if user is administrator.
     *
     * @return bool
     */
    public function isAdministrator(): bool
    {
        return $this->isLoggedIn() AND auth()->user()->roles('name', 'administrator')->first();
    }
}
