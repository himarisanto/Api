<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Server;
use App\Http\Controllers\Controller;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::all();
        return response()->json([
            'status' => true,
            'message' => 'Data Ditemukan',
            'data' => $servers
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_koneksi' => 'required|string',
            'driver' => 'required|string',
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $server = Server::create($request->all());

        if ($server) {
            $message = 'Data berhasil dimasukkan.';
            $status = 202;
        } else {
            $message = 'Gagal memasukkan data.';
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
        $request->validate([
            'nama_koneksi' => 'string',
            'driver' => 'string',
            'host' => 'string',
            'port' => 'integer',
            'username' => 'string',
            'password' => 'string',
            'note' => 'nullable|string',
        ]);

        if ($server->update($request->all())) {
            $message = 'Data berhasil diupdate.';
        } else {
            $message = 'Gagal update data';
        }
        return response()->json(['message' => $message, 'data' => $server], $server->wasChanged() ? 200 : 400);
    }

    public function destroy(string $id)
    {
        $server = Server::find($id);

        if (empty($server)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
        $server->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses melakukan delete data'
        ]);
    }
}
