<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $role = $user->role;

        if ($role!=='admin') {
            return response()->json([
                'errors'=>[
                    'message'=>[
                        'unauthorized'
                    ]
                ]
            ])->setStatusCode(401);
        }
        
        return $next($request);
    }
}
