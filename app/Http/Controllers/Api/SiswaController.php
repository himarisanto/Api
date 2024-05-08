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
    public function GetTotalSiswa()
    {
        $totalSiswa = Siswa::count();
        return response()->json([
            'status' => true,
            'message' => 'Jumlah total siswa',
            'data' => $totalSiswa
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'no_absen' => 'required|integer',
            'nama' => 'required|string',
            'kelas' => 'required|string',
            'jurusan' => 'required|string',
        ], [
            'gambar.required' => 'Gambar masih Kosong',
            'no_absen.required' => 'No_absen masih kosong',
            'nama.required' => 'Nama masih Kosong',
            'kelas.required' => 'kelas masih kosong',
            'jurusan.required' => 'Jurusan Masih kosong',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
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
        $dataSiswa = Siswa::find($id);

        if (empty($dataSiswa)) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validateDataSiswa = [];
        if ($request->hasFile('gambar')) {
            $validateDataSiswa['gambar'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }
        if ($request->has('no_absen')) {
            $validateDataSiswa['no_absen'] = 'required|integer';
        }
        if ($request->has('nama')) {
            $validateDataSiswa['nama'] = 'required|string';
        }
        if ($request->has('kelas')) {
            $validateDataSiswa['kelas'] = 'required|string';
        }
        if ($request->has('jurusan')) {
            $validateDataSiswa['jurusan'] = 'required|string';
        }

        if (!empty($validateDataSiswa)) {
            $validated = $request->validate($validateDataSiswa);
            if ($dataSiswa->update($validated)) {
                $message = 'Data Berhasil diupdate.';
            } else {
                $message = 'Gagal Update data kedatabase';
            }
        } else {
            $message = 'Tidak ada yang diupdate.';
        }

        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('public/images');
            $imageNama = basename($imagePath);
            $dataSiswa->gambar = $imageNama;
        }

        if ($request->has('no_absen')) {
            $dataSiswa->no_absen = $request->no_absen;
        }
        if ($request->has('nama')) {
            $dataSiswa->nama = $request->nama;
        }
        if ($request->has('kelas')) {
            $dataSiswa->kelas = $request->kelas;
        }
        if ($request->has('jurusan')) {
            $dataSiswa->jurusan = $request->jurusan;
        }

        $dataSiswa->save();

        return response()->json(['message' => $message, 'data' => $dataSiswa], $dataSiswa->wasChanged() ? 200 : 400);
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
