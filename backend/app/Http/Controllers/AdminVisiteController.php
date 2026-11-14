<?php

namespace App\Http\Controllers;

use App\Models\Visite;
use Illuminate\Http\Request;

class AdminVisiteController extends Controller
{
    // Toutes les visites, avec le client et le bien
    public function index()
    {
        return response()->json(
            Visite::with(['utilisateur', 'annonce.bien'])->orderBy('date_visite', 'desc')->get()
        );
    }

    // Confirmer, refuser, annuler ou reprogrammer (F20)
    public function traiter(Request $request, $id)
    {
        $data = $request->validate([
            'statut'      => 'required|in:confirmee,refusee,annulee',
            'date_visite' => 'nullable|date|after:now', // reprogrammation
        ]);

        $visite = Visite::findOrFail($id);
        $visite->statut = $data['statut'];

        if (!empty($data['date_visite'])) {
            $visite->date_visite = $data['date_visite'];
        }
        $visite->save();

        return response()->json($visite->load(['utilisateur', 'annonce.bien']));
    }
}