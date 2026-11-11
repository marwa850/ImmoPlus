<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    // LISTE : recherche + filtres + tri + pagination
    public function index(Request $request)
    {
        // On charge l'annonce avec son bien, ses images et ses détails
        $query = Annonce::with(['bien.images', 'bien.appartement', 'bien.villa', 'bien.maison'])
            // Le public ne voit pas les brouillons ni les annonces archivées
            ->whereNotIn('statut', ['brouillon', 'archivee']);

        // RECHERCHE par titre, ville ou adresse (F10)
        if ($request->filled('search')) {
            $mot = $request->search;
            $query->where(function ($q) use ($mot) {
                $q->where('titre', 'like', "%$mot%")
                  ->orWhereHas('bien', function ($b) use ($mot) {
                      $b->where('ville', 'like', "%$mot%")
                        ->orWhere('adresse', 'like', "%$mot%");
                  });
            });
        }

        // FILTRES (F11)
        if ($request->filled('ville')) {
            $query->whereHas('bien', fn ($b) => $b->where('ville', $request->ville));
        }
        if ($request->filled('type_bien')) {
            $query->whereHas('bien', fn ($b) => $b->where('type_bien', $request->type_bien));
        }
        if ($request->filled('type_transaction')) {
            $query->where('type_transaction', $request->type_transaction);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }
        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        // TRI (F12) : par prix ou par date, croissant ou décroissant
        $tri = $request->get('tri', 'date');
        $ordre = $request->get('ordre', 'desc') === 'asc' ? 'asc' : 'desc';
        $colonne = $tri === 'prix' ? 'prix' : 'created_at';
        $query->orderBy($colonne, $ordre);

        // PAGINATION (F9) : 9 annonces par page
        return response()->json($query->paginate(9));
    }

    // DÉTAIL d'une annonce (F13)
    public function show($id)
    {
        $annonce = Annonce::with(['bien.images', 'bien.appartement', 'bien.villa', 'bien.maison'])
            ->whereNotIn('statut', ['brouillon', 'archivee'])
            ->findOrFail($id);

        return response()->json($annonce);
    }
}