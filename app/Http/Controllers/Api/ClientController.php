<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return response()->json([
            'success' => true,
            'data' => $clients
        ]);
    }

    public function store(Request $request)
    {
        $client = Client::create($request->validate([
            'nom' => 'required|string',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string'
        ]));

        return response()->json(['success' => true, 'data' => $client]);
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json(['success' => true, 'data' => $client]);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->all());
        return response()->json(['success' => true, 'data' => $client]);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return response()->json(['success' => true]);
    }

    public function search(Request $request)
    {
        $term = $request->get('q');
        $clients = Client::where('nom', 'LIKE', "%{$term}%")
            ->orWhere('telephone', 'LIKE', "%{$term}%")
            ->get();
        return response()->json(['success' => true, 'data' => $clients]);
    }
}
