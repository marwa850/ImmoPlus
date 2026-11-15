<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Offre;
use Illuminate\Http\Request;
use App\Services\Notifier;

class OffreController extends Controller
{
    // LISTE : seulement les offres du client connecté (R7)
    public function index(Request $request)
    {
        return response()->json(
            Offre::with('annonce.bien')
                ->where('utilisateur_id', $request->user()->id)
                ->orderBy('date_offre', 'desc')
                ->get()
        );
    }

    // ENVOYER une offre (F16)
    public function store(Request $request)
    {
        $data = $request->validate([
            'annonce_id' => 'required|exists:annonces,id',
            'montant'    => 'required|numeric|min:1',
            'message'    => 'nullable|string|max:500',
        ]);

        $annonce = Annonce::findOrFail($data['annonce_id']);

        // R1 : l'annonce doit être disponible
        if ($annonce->statut !== 'disponible') {
            return response()->json(['message' => "Cette annonce n'est plus disponible"], 422);
        }

        // R3 : pas deux offres identiques sur le même bien
        $doublon = Offre::where('utilisateur_id', $request->user()->id)
            ->where('annonce_id', $annonce->id)
            ->where('montant', $data['montant'])
            ->whereIn('statut', ['en_attente', 'acceptee'])
            ->exists();

        if ($doublon) {
            return response()->json(['message' => 'Vous avez déjà envoyé cette offre'], 422);
        }

        $offre = Offre::create([
            'utilisateur_id' => $request->user()->id,
            'annonce_id'     => $annonce->id,
            'montant'        => $data['montant'],
            'message'        => $data['message'] ?? null,
            'date_offre'     => now(),
            'statut'         => 'en_attente',
        ]);
        
        Notifier::aLAdmin(
            $request->user()->prenom . ' ' . $request->user()->nom . ' propose ' . number_format($data['montant'], 0, ',', ' ') . ' MAD pour « ' . $annonce->titre . ' »',
            'offre'
        );

        return response()->json($offre, 201);
    }
}