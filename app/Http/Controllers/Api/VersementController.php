<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Versement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VersementController extends Controller
{
    public function store(Request $request)
    {
        $versement = Versement::create([
            'commercial_id' => Auth::id(),
            'montant' => $request->montant,
            'reference' => $request->reference,
            'date_versement' => $request->date_versement ?? now(),
            'notes' => $request->notes,
            'valide' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Versement enregistré',
            'data' => $versement
        ]);
    }

    public function myVersements()
    {
        $versements = Versement::where('commercial_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $versements]);
    }
}
