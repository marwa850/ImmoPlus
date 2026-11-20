<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        h1 { color: #4f46e5; margin-bottom: 4px; }
        p.sous-titre { color: #64748b; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #eef2ff; text-align: left; padding: 8px; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <h1>ImmoPlus : planning des visites</h1>
    <p class="sous-titre">
        Du {{ $debut->format('d/m/Y') }} au {{ $fin->format('d/m/Y') }}
        ({{ $mode === 'semaine' ? 'semaine' : 'jour' }})
    </p>

    <table>
        <tr><th>Date et heure</th><th>Client</th><th>Téléphone</th><th>Bien</th><th>Ville</th><th>Statut</th></tr>
        @forelse ($visites as $v)
            <tr>
                <td>{{ date('d/m/Y H:i', strtotime($v->date_visite)) }}</td>
                <td>{{ $v->utilisateur->prenom }} {{ $v->utilisateur->nom }}</td>
                <td>{{ $v->utilisateur->telephone }}</td>
                <td>{{ $v->annonce->titre }}</td>
                <td>{{ $v->annonce->bien->ville }}</td>
                <td>{{ str_replace('_', ' ', $v->statut) }}</td>
            </tr>
        @empty
            <tr><td colspan="6">Aucune visite sur cette période.</td></tr>
        @endforelse
    </table>
</body>
</html>