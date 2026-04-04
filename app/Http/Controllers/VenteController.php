<?php

namespace App\Http\Controllers;

use App\Services\VenteService;
use App\Http\Requests\VenteRequest;
use Illuminate\Http\Request;

class VenteController extends Controller
{
    protected $venteService;

    public function __construct(VenteService $venteService)
    {
        $this->venteService = $venteService;
    }

    public function index(Request $request)
    {
        $ventes = $this->venteService->getVentesFiltrees($request->all());
        $produits = \App\Models\Produit::all();
        $commerciaux = \App\Models\User::where('role', 'terrain')->get();
        
        return view('ventes.index', compact('ventes', 'produits', 'commerciaux'));
    }

    public function create()
    {
        $produits = \App\Models\Produit::where('stock', '>', 0)->get();
        $clients = \App\Models\Client::all();
        $commerciaux = \App\Models\User::where('role', 'terrain')->get();
        
        return view('ventes.create', compact('produits', 'clients', 'commerciaux'));
    }

    public function store(VenteRequest $request)
    {
        try {
            $vente = $this->venteService->creerVente($request->validated());
            return redirect()->route('ventes.show', $vente->id)
                ->with('success', 'Vente enregistrée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $vente = Vente::with(['produit', 'client', 'commercial'])->findOrFail($id);
        return view('ventes.show', compact('vente'));
    }

    public function edit($id)
    {
        $vente = Vente::findOrFail($id);
        $produits = \App\Models\Produit::all();
        $clients = \App\Models\Client::all();
        $commerciaux = \App\Models\User::where('role', 'terrain')->get();
        
        return view('ventes.edit', compact('vente', 'produits', 'clients', 'commerciaux'));
    }

    public function update(VenteRequest $request, $id)
    {
        try {
            // Note: La mise à jour d'une vente est complexe car il faut gérer le stock
            // Pour simplifier, on permet seulement de modifier certaines informations
            $vente = Vente::findOrFail($id);
            $vente->update($request->only(['facture_ref', 'notes']));
            
            return redirect()->route('ventes.show', $id)
                ->with('success', 'Vente mise à jour avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function statistiques(Request $request)
    {
        $statistiques = $this->venteService->getStatistiquesVentes(
            $request->date_debut,
            $request->date_fin
        );
        
        $ventesParProduit = $this->venteService->getVentesParProduit(
            $request->date_debut,
            $request->date_fin
        );
        
        $ventesParCommercial = $this->venteService->getVentesParCommercial(
            $request->date_debut,
            $request->date_fin
        );
        
        return view('ventes.statistiques', compact('statistiques', 'ventesParProduit', 'ventesParCommercial'));
    }

    public function rapportJournalier()
    {
        $today = now()->format('Y-m-d');
        $ventes = Vente::whereDate('date_vente', $today)
            ->with(['produit', 'client', 'commercial'])
            ->get();
            
        $total = $ventes->sum('montant_total');
        
        return view('ventes.rapport-journalier', compact('ventes', 'total'));
    }
}