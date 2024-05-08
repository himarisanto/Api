<?php 

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Server;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::all();
        return response()->json([
            'status' => true,
            'message' => 'Data Ditemukan di database',
            'data' => $servers
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_koneksi' => 'required|string',
            'driver' => 'required|string',
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string',
            'note' => 'nullable|string',
        ], [
            'nama_koneksi.required' => 'Nama koneksi masih kosong.',
            'driver.required' => 'Driver masih kosong.',
            'host.required' => 'Host masih kosong.',
            'port.required' => 'Port masih kosong.',
            'port.integer' => 'Port harus berupa angka.',
            'username.required' => 'Username masih kosong.',
            'password.required' => 'Password masih kosong.',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $server = Server::create($request->all());

        if ($server) {
            $message = 'Data berhasil dimasukkan kedatabase.';
            $status = 202;
        } else {
            $message = 'Gagal memasukkan data kedatabase.';
            $status = 400;
        }
        return response()->json(['message' => $message, 'data' => $server], $status);
    }
    public function show(string $id)
    {
        $data = Server::find($id);

        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan di database'
            ], 404);
        }
    }
    public function update(Request $request, Server $server)
    {
        $validateData = [];
        if ($request->has('nama_koneksi')) {
            $validateData['nama_koneksi'] = 'string|required';
        }
        if ($request->has('driver')) {
            $validateData['driver'] = 'string|required';
        }
        if ($request->has('host')) {
            $validateData['host'] = 'string|required';
        }
        if ($request->has('port')) {
            $validateData['port'] = 'integer|required';
        }
        if ($request->has('username')) {
            $validateData['username'] = 'string|required';
        }
        if ($request->has('password')) {
            $validateData['password'] = 'string|required';
        }
        if ($request->has('note')) {
            $validateData['note'] = 'nullable|string';
        }
        if (!empty($validateData)) {
            $validated = $request->validate($validateData);
            if ($server->update($validated)) {
                $message = 'Data berhasil diupdate.';
            } else {
                $message = 'Gagal update data kedatabase';
            }
        } else {
            $message = 'Tidak ada yang diupdate';
        }

        return response()->json(['message' => $message, 'data' => $server], $server->wasChanged() ? 200 : 400);
    }
    public function destroy(string $id)
    {
        $server = Server::find($id);

        if (empty($server)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan didatabase',
            ], 404);
        }
        $server->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses melakukan delete data'
        ]);
    }
}
