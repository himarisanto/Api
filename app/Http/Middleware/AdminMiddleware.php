<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
    
        if ($user && $user->role === 'admin') {
            return $next($request);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized. Anda tidak memiliki izin untuk mengakses sumber daya ini.'
        ], 403);
    }
}
