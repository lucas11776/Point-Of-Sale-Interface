<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AllowIfAdministrator
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
        $administator = auth()->user()->roles->where(['name' => 'administrator'])->first();

        if(! $administator) {
            return response()->json(
                ['Unauthorized Access.'], JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }
}
