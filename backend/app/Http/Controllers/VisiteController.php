<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Visite;
use Illuminate\Http\Request;
use App\Services\Notifier;

class VisiteController extends Controller
{
    // LISTE : seulement les visites du client connecté (R7)
    public function index(Request $request)
    {
        return response()->json(
            Visite::with('annonce.bien')
                ->where('utilisateur_id', $request->user()->id)
                ->orderBy('date_visite', 'desc')
                ->get()
        );
    }

    // DEMANDER une visite (F15)
    public function store(Request $request)
    {
        $data = $request->validate([
            'annonce_id'  => 'required|exists:annonces,id',
            'date_visite' => 'required|date|after:now', // R4 : date dans le futur
            'commentaire' => 'nullable|string|max:500',
        ]);

        $annonce = Annonce::findOrFail($data['annonce_id']);

        // R1 : l'annonce doit être disponible
        if ($annonce->statut !== 'disponible') {
            return response()->json(['message' => "Cette annonce n'est plus disponible"], 422);
        }

        // R3 : pas deux visites le même jour sur le même bien
        $jour = date('Y-m-d', strtotime($data['date_visite']));
        $doublon = Visite::where('utilisateur_id', $request->user()->id)
            ->where('annonce_id', $annonce->id)
            ->whereDate('date_visite', $jour)
            ->whereNotIn('statut', ['refusee', 'annulee'])
            ->exists();

        if ($doublon) {
            return response()->json(['message' => 'Vous avez déjà une visite ce jour-là pour ce bien'], 422);
        }

        $visite = Visite::create([
            'utilisateur_id' => $request->user()->id,
            'annonce_id'     => $annonce->id,
            'date_visite'    => $data['date_visite'],
            'commentaire'    => $data['commentaire'] ?? null,
            'statut'         => 'en_attente',
        ]);

        Notifier::aLAdmin(
            $request->user()->prenom . ' ' . $request->user()->nom . ' demande une visite pour « ' . $annonce->titre . ' »',
            'visite'
        );
        return response()->json($visite, 201);
    }

    // ANNULER sa propre visite
    public function annuler(Request $request, $id)
    {
        $visite = Visite::where('utilisateur_id', $request->user()->id)->findOrFail($id);
        $visite->update(['statut' => 'annulee']);

        return response()->json($visite);
    }
}