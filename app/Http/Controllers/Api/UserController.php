<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PharIo\Manifest\Email;



class UserController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $data_users = [];

        $loginUser = Auth::guard('api')->user();
        if ($loginUser && $loginUser->level === 'admin') {
            $users = User::orderBy('name', 'asc')->paginate($perPage);
        } else {
            $users = User::where('id', $loginUser->id)->paginate($perPage);
        }

        foreach ($users as $user) {
            $user->gambar = '/storage/images/' . $user->gambar;
            $data_users[] = $user;
        }

        return response()->json([
            'status' => true,
            'message' => 'Sukses mengambil data',
            'data' => $data_users,
            'meta' => [
                'currentpage' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'last_page' => $users->lastPage(),
            ],
        ], 200);
    }

    public function GetTotalUsers()
    {
        $totalUsers = User::count();

        return response()->json([
            'status' => true,
            'message' => 'Jumlah total User',
            'data' => $totalUsers
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'level' => 'required|in:admin,user'
        ], [
            'gambar.required' => 'Gambar masih Kosong',
            'name.required' => 'Name masih kosong',
            'email.required' => 'Email masih kosong',
            'Password.required' => 'Password Masih Kosong',
            'level.required' => 'Level masih kosong',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
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
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        $validateData = [];
        if ($request->hasFile('gambar')) {
            $validateData['gambar'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }
        if ($request->has('name')) {
            $validateData['name'] = 'string|max:255';
        }
        if ($request->has('email')) {
            $validateData['email'] = 'string|email|max:255|unique:users,email,' . $id;
        }
        if ($request->has('password')) {
            $validateData['password'] = 'string|min:8';
        }
        if ($request->has('level')) {
            $validateData['level'] = 'in:admin,user';
        }
        $validator = validator::make($request->all(), $validateData);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal melakukan update Data',
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
            $user->password = hash::make($request->password);
        }
        $user->level = strtolower($request->input('level', $user->level));

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses melakukan update data',
            'data' => $user
        ]);
    }
    public function destroy($id)
    {
        $loginUser = Auth::guard('api')->user();

        // cek periksa pengguna apakah admin atau user
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
