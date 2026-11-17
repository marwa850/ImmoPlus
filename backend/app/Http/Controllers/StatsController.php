<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Offre;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // ÉVOLUTION MENSUELLE (F28) : offres acceptées regroupées par mois
    public function evolution(Request $request)
    {
        $request->validate([
            'debut' => 'nullable|date',
            'fin'   => 'nullable|date|after_or_equal:debut',
        ]);

        // Période par défaut : les 12 derniers mois
        $debut = $request->debut ?: now()->subMonths(11)->startOfMonth()->toDateString();
        $fin   = $request->fin   ?: now()->endOfMonth()->toDateString();

        $lignes = Offre::where('offres.statut', 'acceptee')
            ->join('annonces', 'annonces.id', '=', 'offres.annonce_id')
            ->whereDate('offres.date_offre', '>=', $debut)
            ->whereDate('offres.date_offre', '<=', $fin)
            ->select(
                DB::raw("DATE_FORMAT(offres.date_offre, '%Y-%m') as mois"),
                'annonces.type_transaction',
                DB::raw('SUM(offres.montant) as total')
            )
            ->groupBy('mois', 'annonces.type_transaction')
            ->get();

        // Liste de tous les mois de la période (même ceux à 0)
        $mois = [];
        $courant = Carbon::parse($debut)->startOfMonth();
        $limite = Carbon::parse($fin)->startOfMonth();
        while ($courant <= $limite) {
            $cle = $courant->format('Y-m');
            $mois[$cle] = ['mois' => $cle, 'ventes' => 0, 'locations' => 0];
            $courant->addMonth();
        }

        foreach ($lignes as $l) {
            $champ = $l->type_transaction === 'vente' ? 'ventes' : 'locations';
            $mois[$l->mois][$champ] = (float) $l->total;
        }

        return response()->json(array_values($mois));
    }
}