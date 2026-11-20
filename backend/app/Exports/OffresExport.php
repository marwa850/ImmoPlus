<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OffresExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $offres) {}

    public function collection(): Enumerable
    {
        return $this->offres;
    }

    public function headings(): array
    {
        return ['ID', 'Client', 'Email', 'Annonce', 'Montant (MAD)', 'Message', 'Date', 'Statut'];
    }

    public function map($o): array
    {
        return [
            $o->id,
            $o->utilisateur->prenom . ' ' . $o->utilisateur->nom,
            $o->utilisateur->email,
            $o->annonce->titre,
            $o->montant,
            $o->message,
            date('d/m/Y H:i', strtotime($o->date_offre)),
            $o->statut,
        ];
    }
}