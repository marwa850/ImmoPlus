<?php

namespace App\Services;

use App\Models\Annonce;
use App\Models\Offre;
use App\Models\Visite;
use Carbon\Carbon;

class ExportService
{
    // Toutes les annonces avec leur bien (F29)
    public function annonces()
    {
        return Annonce::with('bien')->orderBy('id')->get();
    }

    // Toutes les offres avec le client et l'annonce (F29)
    public function offres()
    {
        return Offre::with(['utilisateur', 'annonce'])->orderBy('date_offre', 'desc')->get();
    }

    // Planning des visites d'un jour ou d'une semaine (F30)
    public function planning(string $mode, string $date): array
    {
        $jour = Carbon::parse($date);

        if ($mode === 'semaine') {
            $debut = $jour->copy()->startOfWeek();
            $fin = $jour->copy()->endOfWeek();
        } else {
            $debut = $jour->copy()->startOfDay();
            $fin = $jour->copy()->endOfDay();
        }

        $visites = Visite::with(['utilisateur', 'annonce.bien'])
            ->whereBetween('date_visite', [$debut, $fin])
            ->whereIn('statut', ['en_attente', 'confirmee'])
            ->orderBy('date_visite')
            ->get();

        return [
            'visites' => $visites,
            'debut'   => $debut,
            'fin'     => $fin,
            'mode'    => $mode,
        ];
    }
}