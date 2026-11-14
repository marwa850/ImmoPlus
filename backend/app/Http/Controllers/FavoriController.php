<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Favori;
use Illuminate\Http\Request;

class FavoriController extends Controller
{
    private array $relations = ['bien.images', 'bien.appartement', 'bien.villa', 'bien.maison'];

    // LISTE : seulement les annonces favorites de l'utilisateur connecté (R7)
    public function index(Request $request)
    {
        $annonces = Annonce::with($this->relations)
            ->whereIn('id', $request->user()->favoris()->pluck('annonce_id'))
            ->get();

        return response()->json($annonces);
    }

    // BASCULER : ajoute le favori s'il n'existe pas, le retire sinon
    public function basculer(Request $request, $annonceId)
    {
        Annonce::findOrFail($annonceId);

        $favori = Favori::where('utilisateur_id', $request->user()->id)
            ->where('annonce_id', $annonceId)
            ->first();

        if ($favori) {
            $favori->delete();
            return response()->json(['favori' => false]);
        }

        Favori::create([
            'utilisateur_id' => $request->user()->id,
            'annonce_id'     => $annonceId,
        ]);

        return response()->json(['favori' => true], 201);
    }

    // IDENTIFIANTS : pour colorer les cœurs sur la liste
    public function ids(Request $request)
    {
        return response()->json($request->user()->favoris()->pluck('annonce_id'));
    }
}