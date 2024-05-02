<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;



class UserController extends Controller
{

    public function index(Request $request)
    {
        $data_users = [];
    
        $loginUser = Auth::guard('api')->user();
        if ($loginUser && $loginUser->level === 'admin') {
            $users = User::all();
        } else {
            $users = User::where('id', $loginUser->id)->get();
        }
    
        foreach ($users as $user) {
            $user->gambar = '/storage/images/' . $user->gambar;
            $data_users[] = $user;
        }
    
        return response()->json([
            'status' => true,
            'message' => 'Sukses mengambil data',
            'data' => $data_users
        ], 200);
    }
    

    public function store(Request $request)
    {
        $rules = [
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'level' => 'required|in:admin,user'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memasukkan data',
                'data' => $validator->errors()
            ]);
        }

        $gambarPath = $request->file('gambar')->store('public/images');
        $gambarName = basename($gambarPath);

        $user = User::create([
            'gambar' => $gambarName,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => $request->level
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Sukses memasukkan data',
            'data' => $user
        ], 201);
    }
    public function show($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $rules = [
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $id,
            'password' => 'string|min:8',
            'level' => 'in:admin,user'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal melakukan update data',
                'data' => $validator->errors()
            ], 400);
        }

        if ($request->hasFile('gambar')) {
            if ($user->gambar) {
                Storage::delete('public/images/' . $user->gambar);
            }
            $gambarPath = $request->file('gambar')->store('public/images');
            $gambarName = basename($gambarPath);
            $user->gambar = $gambarName;
        }

        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->level = strtolower($request->input('level', $user->level));

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Melakukan update data',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $loginUser = Auth::guard('api')->user();

        // periksa pengguna apakah admin atau user
        if ($loginUser && $loginUser->level ===  'admin') {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan' 
                ], 404);
            }

            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'Sukses Melakukan delete data'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Hanya admin yang bisa menghapus data'
            ], 403);
        }
    }
}
