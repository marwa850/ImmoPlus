<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AdminClientController extends Controller
{
    // Liste des clients (seulement le rôle « client »)
    public function index()
    {
        $roleClient = Role::where('nom', 'client')->first();

        return response()->json(
            User::where('role_id', $roleClient->id)
                ->withCount(['visites', 'offres', 'favoris'])
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    // Modifier les informations d'un client
    public function update(Request $request, $id)
    {
        $client = User::findOrFail($id);

        $data = $request->validate([
            'nom'       => 'required|string|max:50',
            'prenom'    => 'required|string|max:50',
            'email'     => 'required|email|max:100|unique:utilisateurs,email,' . $client->id, // R9
            'telephone' => 'nullable|string|max:20',
        ]);

        $client->update($data);

        return response()->json($client);
    }

    // Activer / désactiver un compte
    public function basculerActif($id)
    {
        $client = User::findOrFail($id);

        // On ne désactive jamais un administrateur
        if ($client->estAdmin()) {
            return response()->json(['message' => 'Impossible de désactiver un administrateur'], 422);
        }

        $client->update(['actif' => !$client->actif]);

        // Un compte désactivé perd tous ses tokens : il est déconnecté immédiatement
        if (!$client->actif) {
            $client->tokens()->delete();
        }

        return response()->json($client);
    }
}