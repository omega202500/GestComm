<?php

namespace App\Http\Controllers;

use App\Models\versement;
use Illuminate\Http\Request;

class VersementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function count()
    {
        return response()->json([
            'success' => true,
            'count'   => \App\Models\Versement::count(),
        ]);
    }
    
    public function stats()
    {
        return response()->json([
            'success'    => true,
            'total'      => \App\Models\Versement::sum('montant'),
            'valides'    => \App\Models\Versement::where('statut', 'valide')->count(),
            'en_attente' => \App\Models\Versement::where('statut', 'en_attente')->count(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\versement  $versement
     * @return \Illuminate\Http\Response
     */
    public function show(versement $versement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\versement  $versement
     * @return \Illuminate\Http\Response
     */
    public function edit(versement $versement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\versement  $versement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, versement $versement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\versement  $versement
     * @return \Illuminate\Http\Response
     */
    public function destroy(versement $versement)
    {
        //
    }
 public function countPending()
{
    $count = \App\Models\Versement::where('valide', false)->count();
    return response()->json(['count' => $count]);
}
}
