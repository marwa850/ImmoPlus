<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnnoncesExport implements FromCollection, WithHeadings, WithMapping
{
    // Les données viennent du service, cette classe ne fait que la mise en forme
    public function __construct(private Collection $annonces) {}

    public function collection(): Enumerable
    {
        return $this->annonces;
    }

    public function headings(): array
    {
        return ['ID', 'Titre', 'Type de bien', 'Transaction', 'Prix (MAD)', 'Ville', 'Adresse', 'Superficie (m²)', 'Statut', 'Date de publication'];
    }

    public function map($a): array
    {
        return [
            $a->id,
            $a->titre,
            $a->bien->type_bien,
            $a->type_transaction,
            $a->prix,
            $a->bien->ville,
            $a->bien->adresse,
            $a->bien->superficie,
            $a->statut,
            $a->created_at->format('d/m/Y'),
        ];
    }
}