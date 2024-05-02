<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $imagePath = 'public/images/gambar_register.png';

        // periksa gambar
        if (Storage::exists($imagePath)) {
            $gambarName = basename($imagePath);
        } else {
            $gambarName = null;
        }

        $user = User::create([
            'gambar' => $gambarName,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null,
        ]);

        return response()->json(['message' => 'Register Success', 'user' => $user]);
    }

 
    public function getRegisterImage()
    {
        $imagePath = '/storage/images/gambar_register.png';

        if (Storage::exists($imagePath)) {
            $imageContent = Storage::get($imagePath);
            return response($imageContent)->header('Content-Type', 'image/jpeg');
        } else {
            return response()->json(['message' => 'Gambar register tidak ditemukan'], 404);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;

            return response()->json(['message' => 'Login Sukses', 'token' => $token, 'user' => $user]);
        } else {
            return response()->json(['message' => 'Login gagal, silakan periksa kembali email dan password Anda.'], 401);
        }
    }
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)])
            : response()->json(['message' => __($status)], 400);
    }
}
 