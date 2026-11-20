<?php

namespace App\Http\Controllers;

use App\Exports\AnnoncesExport;
use App\Exports\OffresExport;
use App\Services\ExportService;
use App\Services\StatsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __construct(
        private ExportService $exports,
        private StatsService $stats
    ) {}

    // Excel : liste des annonces (F29)
    public function annonces()
    {
        return Excel::download(new AnnoncesExport($this->exports->annonces()), 'annonces.xlsx');
    }

    // Excel : liste des offres (F29)
    public function offres()
    {
        return Excel::download(new OffresExport($this->exports->offres()), 'offres.xlsx');
    }

    // PDF : planning des visites par jour ou par semaine (F30)
    public function visites(Request $request)
    {
        $data = $request->validate([
            'mode' => 'required|in:jour,semaine',
            'date' => 'required|date',
        ]);

        $planning = $this->exports->planning($data['mode'], $data['date']);

        return Pdf::loadView('pdf.planning', $planning)->download('planning-visites.pdf');
    }

    // PDF : rapport de statistiques (F31)
    public function statistiques()
    {
        return Pdf::loadView('pdf.stats', [
            'stats'     => $this->stats->admin(),
            'evolution' => $this->stats->evolution(null, null),
        ])->download('rapport-statistiques.pdf');
    }
}