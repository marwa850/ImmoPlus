<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Services\Notifier;
use Illuminate\Http\Request;

class AdminOffreController extends Controller
{
    public function index()
    {
        return response()->json(
            Offre::with(['utilisateur', 'annonce.bien'])->orderBy('date_offre', 'desc')->get()
        );
    }

    // Accepter ou refuser (F21)
    public function traiter(Request $request, $id)
    {
        $data = $request->validate(['statut' => 'required|in:acceptee,refusee']);

        $offre = Offre::with('annonce')->findOrFail($id);

        // On ne traite qu'une offre en attente
        if ($offre->statut !== 'en_attente') {
            return response()->json(['message' => 'Cette offre a déjà été traitée'], 422);
        }

        $offre->update(['statut' => $data['statut']]);

        // R5 : offre acceptée → l'annonce passe à « réservée »
        if ($data['statut'] === 'acceptee') {
            $offre->annonce->update(['statut' => 'reservee']);
        }

        // F23 : le client est notifié dans les deux cas (acceptée ou refusée)
        Notifier::vers(
            $offre->utilisateur_id,
            'Votre offre pour « ' . $offre->annonce->titre . ' » a été ' . ($data['statut'] === 'acceptee' ? 'acceptée' : 'refusée'),
            'offre'
        );

        return response()->json($offre->load(['utilisateur', 'annonce.bien']));
    }
}