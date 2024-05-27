<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Admin;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
       
        if (!$request->header('Authorization')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Extract token from the Authorization header
        $token = explode(' ', $request->header('Authorization'))[1];

     
        // $admin = Admin::where('remember_token', $token)->first();
        $admin = Admin::where('token', $token)->first();

    
        if (!$admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->admin = $admin;

        return $next($request);
    }
}
