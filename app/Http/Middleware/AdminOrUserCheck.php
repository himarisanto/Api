<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOrUserCheck
{
    public function handle(Request $request, Closure $next)
    {
        // Memeriksa apakah pengguna yang masuk adalah admin atau user
        if ($request->user() && $request->user()->level === 'admin') {
            return $next($request); // Lanjutkan ke fungsi index jika pengguna adalah admin
        } elseif ($request->user() && $request->user()->level === 'user') {
            $request->merge(['user_id' => $request->user()->id]); // Menambahkan user_id ke dalam request untuk filter data
            return $next($request); // Lanjutkan ke fungsi index jika pengguna adalah user
        }

        return response()->json([
            'status' => false,
            'message' => 'Unauthorized. Only admin or user can access this resource.'
        ], 401);
    }
}
