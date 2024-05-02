<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        if ($user->level == 'admin' || ($user->level == 'user' && $role == 'user')) {
            return $next($request);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unauthorized.'
        ], 403);
    }
}
