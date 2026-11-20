<?php

namespace App\Services;

use App\Models\Annonce;
use App\Models\Offre;
use App\Models\User;
use App\Models\Visite;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsService
{
    // Chiffres clés de l'administrateur (F26, F27)
    public function admin(): array
    {
        $nbOffres = Offre::count();
        $nbAcceptees = Offre::where('statut', 'acceptee')->count();

        return [
            'disponibles'  => Annonce::where('statut', 'disponible')->count(),
            'reservees'    => Annonce::where('statut', 'reservee')->count(),
            'vendues'      => Annonce::where('statut', 'vendue')->count(),
            'louees'       => Annonce::where('statut', 'louee')->count(),
            'visites'      => Visite::count(),
            'offres'       => $nbOffres,
            'taux'         => $nbOffres > 0 ? round($nbAcceptees / $nbOffres * 100) : 0,
            'total_ventes' => $this->totalAccepte('vente'),
            'total_loyers' => $this->totalAccepte('location'),
        ];
    }

    // Résumé du client (F19) : seulement ses données (R7)
    public function client(User $u): array
    {
        return [
            'favoris'          => $u->favoris()->count(),
            'visites'          => $u->visites()->count(),
            'visites_attente'  => $u->visites()->where('statut', 'en_attente')->count(),
            'offres'           => $u->offres()->count(),
            'offres_attente'   => $u->offres()->where('statut', 'en_attente')->count(),
            'offres_acceptees' => $u->offres()->where('statut', 'acceptee')->count(),
        ];
    }

    // Évolution mensuelle des ventes et des locations (F28)
    public function evolution(?string $debut, ?string $fin): array
    {
        // Période par défaut : les 12 derniers mois
        $debut = $debut ?: now()->subMonths(11)->startOfMonth()->toDateString();
        $fin   = $fin   ?: now()->endOfMonth()->toDateString();

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

        // Tous les mois de la période, même ceux à 0
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

        return array_values($mois);
    }

    // Somme des offres acceptées selon le type de transaction
    private function totalAccepte(string $type): float
    {
        return (float) Offre::where('statut', 'acceptee')
            ->whereHas('annonce', fn ($q) => $q->where('type_transaction', $type))
            ->sum('montant');
    }
}