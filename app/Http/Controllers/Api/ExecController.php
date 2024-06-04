<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExecController extends Controller
{
    public function executeQuery(Request $request, $query_id)
    {
        // Mencari query berdasarkan ID
        $query = Query::find($query_id);

        // Jika query tidak ditemukan, kembalikan respons dengan kode status 404
        if (is_null($query)) {
            return response()->json(['message' => 'Query not found'], 404);
        }

        try {
            // Menjalankan query dan mengambil hasilnya
            $result = DB::select($query->query);

            // Mengembalikan respons dengan hasil query
            return response()->json(['status' => 'Success', 'data' => $result], 200);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan saat menjalankan query, kembalikan respons dengan kode status 500
            return response()->json(['message' => 'Error executing query', 'error' => $e->getMessage()], 500);
        }
    }
}
