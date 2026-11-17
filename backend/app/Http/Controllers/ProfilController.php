<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    // Modifier ses informations (F3)
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'nom'       => 'required|string|max:50',
            'prenom'    => 'required|string|max:50',
            'email'     => 'required|email|max:100|unique:utilisateurs,email,' . $user->id, // R9
            'telephone' => 'nullable|string|max:20',
        ]);

        $user->update($data);

        return response()->json($user->load('role'));
    }

    // Changer son mot de passe (F3)
    public function motDePasse(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'ancien'       => 'required|string',
            'mot_de_passe' => 'required|string|min:6|confirmed',
        ]);

        // On vérifie l'ancien mot de passe
        if (!Hash::check($data['ancien'], $user->mot_de_passe)) {
            return response()->json(['message' => 'Ancien mot de passe incorrect'], 422);
        }

        $user->update(['mot_de_passe' => $data['mot_de_passe']]); // haché automatiquement

        return response()->json(['message' => 'Mot de passe modifié']);
    }
}