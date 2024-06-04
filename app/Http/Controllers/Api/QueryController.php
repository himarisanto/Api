<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Query;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QueryController extends Controller
{
    public function index()
    {
        $queries = Query::all(); 

        return response()->json($queries, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'query' => 'required|string',
            'last_access' => 'nullable|date',
            'server_id' => 'required|integer|exists:servers,id', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $query = Query::create($request->all());
        return response()->json($query, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255',
            'query' => 'sometimes|required|string',
            'last_access' => 'nullable|date',
            'server_id' => 'sometimes|required|integer|exists:servers,id', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $query = Query::find($id);

        if (is_null($query)) {
            return response()->json(['message' => 'Query not found'], 404);
        }

        $query->update($request->all());
        return response()->json($query, 200);
    }

    public function show($id)
    {
        $query = Query::find($id);

        if (is_null($query)) {
            return response()->json(['message' => 'Query not found'], 404);
        }

        return response()->json($query, 200);
    }

    public function destroy($id)
    {
        $query = Query::find($id);

        if (is_null($query)) {
            return response()->json(['message' => 'Query not found'], 404);
        }

        $query->delete();
        return response()->json(null, 204);
    }
}
