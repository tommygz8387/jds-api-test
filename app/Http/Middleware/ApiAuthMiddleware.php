<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        $authenticate = true;
        
        if (!$token) {
            $authenticate = false;
        }
        $token = str_replace('Bearer ', '', $token);
        $user = User::where('remember_token',$token)->first();
        
        if (!$user) {
            $authenticate = false;
        }else{
            auth()->login($user);
        }
        

        if ($authenticate) {
            return $next($request);
        }else{
            return response()->json([
                'errors'=>[
                    'message'=>[
                        'unauthorized'
                    ]
                ]
            ])->setStatusCode(401);
        }


    }
}
