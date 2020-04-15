<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AllowIfEmployee
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
        $employeeOrAdministrotor = auth()->user()->roles()
            ->where(['name' => 'administrator'])
            ->orWhere(['name' => 'employee'])->first();

        if(! $employeeOrAdministrotor) {
            return response()->json(
                ['Unauthorized Access.'], JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }
}
