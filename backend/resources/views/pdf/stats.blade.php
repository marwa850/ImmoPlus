<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        h1 { color: #4f46e5; margin-bottom: 4px; }
        h2 { margin-top: 25px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #eef2ff; text-align: left; padding: 8px; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
        .droite { text-align: right; }
    </style>
</head>
<body>
    <h1>ImmoPlus : rapport de statistiques</h1>
    <p>Généré le {{ now()->format('d/m/Y H:i') }}</p>

    <h2>Chiffres clés</h2>
    <table>
        <tr><td>Biens disponibles</td><td class="droite">{{ $stats['disponibles'] }}</td></tr>
        <tr><td>Biens réservés</td><td class="droite">{{ $stats['reservees'] }}</td></tr>
        <tr><td>Biens vendus</td><td class="droite">{{ $stats['vendues'] }}</td></tr>
        <tr><td>Biens loués</td><td class="droite">{{ $stats['louees'] }}</td></tr>
        <tr><td>Nombre de visites</td><td class="droite">{{ $stats['visites'] }}</td></tr>
        <tr><td>Nombre d'offres</td><td class="droite">{{ $stats['offres'] }}</td></tr>
        <tr><td>Taux d'acceptation</td><td class="droite">{{ $stats['taux'] }} %</td></tr>
    </table>

    <h2>Montants</h2>
    <table>
        <tr><td>Total des ventes</td><td class="droite">{{ number_format($stats['total_ventes'], 0, ',', ' ') }} MAD</td></tr>
        <tr><td>Total des loyers mensuels</td><td class="droite">{{ number_format($stats['total_loyers'], 0, ',', ' ') }} MAD</td></tr>
    </table>

    <h2>Évolution mensuelle (12 derniers mois)</h2>
    <table>
        <tr><th>Mois</th><th class="droite">Ventes (MAD)</th><th class="droite">Locations (MAD)</th></tr>
        @foreach ($evolution as $m)
            <tr>
                <td>{{ $m['mois'] }}</td>
                <td class="droite">{{ number_format($m['ventes'], 0, ',', ' ') }}</td>
                <td class="droite">{{ number_format($m['locations'], 0, ',', ' ') }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>