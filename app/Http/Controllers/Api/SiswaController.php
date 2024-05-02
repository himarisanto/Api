<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class SiswaController extends Controller
{

    public function index()
    {
        $data_siswa = [];
        $data = Siswa::orderBy('nama', 'asc')->get();

        foreach ($data as $siswa) {
            $siswa->gambar = '/storage/images/' . $siswa->gambar;
            $data_siswa[] = $siswa;
        }

        return response()->json([
            'status' => true,
            'message' => 'Data Ditemukan',
            'data' => $data_siswa
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'no_absen' => 'required|integer',
            'nama' => 'required|string',
            'kelas' => 'required|string',
            'jurusan' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memasukkan data',
                'data' => $validator->errors()
            ]);
        }

        $imagePath = $request->file('gambar')->store('public/images');
        $imageName = basename($imagePath);

        $dataSiswa = new Siswa;
        $dataSiswa->gambar = $imageName;
        $dataSiswa->no_absen = $request->no_absen;
        $dataSiswa->nama = $request->nama;
        $dataSiswa->kelas = $request->kelas;
        $dataSiswa->jurusan = $request->jurusan;
        $dataSiswa->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses memasukkan data'
        ], 201);
    }

    public function show(string $id)
    {
        $data = Siswa::find($id);

        if ($data) {
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $data
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
        // dd($request);
        $dataSiswa = Siswa::find($id);

        if (empty($dataSiswa)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $rules = [
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'no_absen' => 'required|integer',
            'nama' => 'required|string',
            'kelas' => 'required|string',
            'jurusan' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal melakukan update data',
                'data' => $validator->errors()
            ]);
        }



        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('public/images');
            $imageName = basename($imagePath);
            $dataSiswa->gambar = $imageName;
        }

        $dataSiswa->no_absen = $request->no_absen;
        $dataSiswa->nama = $request->nama;
        $dataSiswa->kelas = $request->kelas;
        $dataSiswa->jurusan = $request->jurusan;
        $dataSiswa->save();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Melakukan update data'
        ]);
    }
    public function destroy(string $id)
    {
        $dataSiswa = Siswa::find($id);

        if (empty($dataSiswa)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $dataSiswa->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Melakukan delete data'
        ]);
    }
}
