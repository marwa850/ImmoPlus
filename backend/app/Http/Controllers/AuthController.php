<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // INSCRIPTION : crée uniquement un compte client
    public function register(Request $request)
    {
        // Validation côté serveur (email unique = règle R9)
        $data = $request->validate([
            'nom'          => 'required|string|max:50',
            'prenom'       => 'required|string|max:50',
            'email'        => 'required|email|max:100|unique:utilisateurs,email',
            'telephone'    => 'nullable|string|max:20',
            'mot_de_passe' => 'required|string|min:6|confirmed',
        ]);

        // Le rôle est toujours "client" pour l'inscription publique
        $roleClient = Role::where('nom', 'client')->first();

        $user = User::create([
            'nom'          => $data['nom'],
            'prenom'       => $data['prenom'],
            'email'        => $data['email'],
            'telephone'    => $data['telephone'] ?? null,
            'mot_de_passe' => $data['mot_de_passe'], // haché automatiquement
            'role_id'      => $roleClient->id,
            'actif'        => true,
        ]);

        // On donne directement un token au nouveau client
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie',
            'user'    => $user->load('role'),
            'token'   => $token,
        ], 201);
    }

    // CONNEXION
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'        => 'required|email',
            'mot_de_passe' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        // Email inconnu ou mot de passe incorrect
        if (!$user || !Hash::check($data['mot_de_passe'], $user->mot_de_passe)) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect',
            ], 401);
        }

        // Compte désactivé par l'administrateur
        if (!$user->actif) {
            return response()->json([
                'message' => 'Votre compte est désactivé',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user'    => $user->load('role'),
            'token'   => $token,
        ]);
    }

    // DÉCONNEXION : supprime le token utilisé
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie']);
    }

    // Retourne l'utilisateur connecté (utile pour React)
    public function me(Request $request)
    {
        return response()->json($request->user()->load('role'));
    }
}