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
        return response()->json($servers);
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
            $massage = 'Data berhasil di masukkan.';
            $status =201;
        } else {
            $massage = 'gagal memasukan data.';
            $status = 400;
        }
        return response()->json(['massage' => $massage, 'data' => $server], $status);
    }

    public function show(Server $server)
    {
        return response()->json($server);
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
            $message = 'Data berhasil diUpdate.';
        } else {
            $message = 'Gagal Updata a-data';
        }
        return response()->json(['message' => $message, 'data' => $server], $server->wasChanged() ? 200 : 400);
    }
    public function destroy(Server $server)
    {
        $server->delete();

        return response()->json(null, 204);
    }
}
