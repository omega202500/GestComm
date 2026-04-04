<?php

namespace App\Http\Controllers;

use App\Services\CommandeService;
use App\Http\Requests\CommandeRequest;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    protected $commandeService;

    public function __construct(CommandeService $commandeService)
    {
        $this->commandeService = $commandeService;
    }

    public function index(Request $request)
    {
        $commandes = $this->commandeService->getCommandesFiltrees($request->all());
        $zones = \App\Models\Zone::all();
        $commerciaux = \App\Models\User::where('role', 'terrain')->get();
        
        return view('commandes.index', compact('commandes', 'zones', 'commerciaux'));
    }

    public function create()
    {
        $clients = \App\Models\Client::all();
        $commerciaux = \App\Models\User::where('role', 'terrain')->get();
        $zones = \App\Models\Zone::all();
        
        return view('commandes.create', compact('clients', 'commerciaux', 'zones'));
    }

    public function store(CommandeRequest $request)
    {
        try {
            $commande = $this->commandeService->creerCommande($request->validated());
            return redirect()->route('commandes.show', $commande->id)
                ->with('success', 'Commande créée avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $commande = Commande::with(['client', 'commercial', 'zone', 'livraison'])->findOrFail($id);
        return view('commandes.show', compact('commande'));
    }

    public function edit($id)
    {
        $commande = Commande::findOrFail($id);
        $clients = \App\Models\Client::all();
        $commerciaux = \App\Models\User::where('role', 'terrain')->get();
        $zones = \App\Models\Zone::all();
        
        return view('commandes.edit', compact('commande', 'clients', 'commerciaux', 'zones'));
    }

    public function update(CommandeRequest $request, $id)
    {
        $commande = Commande::findOrFail($id);
        $commande->update($request->validated());
        
        return redirect()->route('commandes.show', $id)
            ->with('success', 'Commande mise à jour avec succès');
    }

    public function updateStatut(Request $request, $id)
    {
        $request->validate(['statut' => 'required|string']);
        
        try {
            $commande = $this->commandeService->mettreAJourStatut($id, $request->statut);
            return response()->json(['success' => true, 'message' => 'Statut mis à jour']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function statistiques(Request $request)
    {
        $statistiques = $this->commandeService->getStatistiquesCommandes(
            $request->date_debut,
            $request->date_fin
        );
        
        $chiffreAffaires = $this->commandeService->getChiffreAffairesParZone(
            $request->date_debut,
            $request->date_fin
        );
        
        return view('commandes.statistiques', compact('statistiques', 'chiffreAffaires'));
    }
}