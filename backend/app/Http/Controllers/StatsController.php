<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Offre;
use App\Models\Visite;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    // TABLEAU DE BORD ADMIN (F26, F27)
    public function admin()
    {
        $nbOffres = Offre::count();
        $nbAcceptees = Offre::where('statut', 'acceptee')->count();

        // Somme des offres acceptées, séparée selon le type de transaction
        $total = function ($type) {
            return Offre::where('statut', 'acceptee')
                ->whereHas('annonce', fn ($q) => $q->where('type_transaction', $type))
                ->sum('montant');
        };

        return response()->json([
            'disponibles'  => Annonce::where('statut', 'disponible')->count(),
            'reservees'    => Annonce::where('statut', 'reservee')->count(),
            'vendues'      => Annonce::where('statut', 'vendue')->count(),
            'louees'       => Annonce::where('statut', 'louee')->count(),
            'visites'      => Visite::count(),
            'offres'       => $nbOffres,
            'taux'         => $nbOffres > 0 ? round($nbAcceptees / $nbOffres * 100) : 0,
            'total_ventes' => (float) $total('vente'),
            'total_loyers' => (float) $total('location'),
        ]);
    }

    // TABLEAU DE BORD CLIENT (F19) : seulement ses propres données (R7)
    public function client(Request $request)
    {
        $u = $request->user();

        return response()->json([
            'favoris'        => $u->favoris()->count(),
            'visites'        => $u->visites()->count(),
            'visites_attente' => $u->visites()->where('statut', 'en_attente')->count(),
            'offres'         => $u->offres()->count(),
            'offres_attente' => $u->offres()->where('statut', 'en_attente')->count(),
            'offres_acceptees' => $u->offres()->where('statut', 'acceptee')->count(),
        ]);
    }
}