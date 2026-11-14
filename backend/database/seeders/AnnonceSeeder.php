<?php

namespace Database\Seeders;

use App\Models\Annonce;
use App\Models\Bien;
use Illuminate\Database\Seeder;

class AnnonceSeeder extends Seeder
{
    public function run(): void
    {
        // Chaque ligne : titre, transaction, prix, type, ville, adresse, superficie, chambres, sdb, détails
        $annonces = [
            ['Appartement lumineux Marina', 'vente', 1200000, 'appartement', 'Casablanca', 'Boulevard de la Corniche', 110, 3, 2, ['etage' => 4, 'ascenseur' => true, 'balcon' => true]],
            ['Studio moderne centre ville', 'location', 4500, 'appartement', 'Rabat', 'Avenue Mohammed V', 40, 1, 1, ['etage' => 2, 'ascenseur' => true, 'balcon' => false]],
            ['Villa avec piscine', 'vente', 4500000, 'villa', 'Marrakech', 'Route de Fès, Palmeraie', 450, 5, 4, ['nombre_etages' => 2, 'piscine' => true, 'jardin' => true, 'garage' => true]],
            ['Villa vue sur mer', 'location', 18000, 'villa', 'Agadir', 'Founty', 300, 4, 3, ['nombre_etages' => 2, 'piscine' => true, 'jardin' => true, 'garage' => true]],
            ['Maison traditionnelle', 'vente', 950000, 'maison', 'Fès', 'Médina', 120, 4, 2, ['nombre_etages' => 2, 'jardin' => false, 'garage' => false]],
            ['Maison familiale avec jardin', 'vente', 1800000, 'maison', 'Agadir', 'Hay Salam', 200, 4, 3, ['nombre_etages' => 2, 'jardin' => true, 'garage' => true]],
            ['Appartement meublé', 'location', 7000, 'appartement', 'Tanger', 'Malabata', 85, 2, 1, ['etage' => 5, 'ascenseur' => true, 'balcon' => true]],
            ['Grand appartement familial', 'vente', 850000, 'appartement', 'Agadir', 'Rue Hassan II', 95, 3, 2, ['etage' => 2, 'ascenseur' => true, 'balcon' => false]],
            ['Villa moderne', 'vente', 3200000, 'villa', 'Casablanca', 'Ain Diab', 380, 5, 4, ['nombre_etages' => 3, 'piscine' => true, 'jardin' => true, 'garage' => true]],
            ['Maison de campagne', 'location', 9000, 'maison', 'Marrakech', 'Route d\'Ourika', 220, 3, 2, ['nombre_etages' => 1, 'jardin' => true, 'garage' => false]],
        ];

        foreach ($annonces as [$titre, $transaction, $prix, $type, $ville, $adresse, $surface, $chambres, $sdb, $details]) {
            $bien = Bien::create([
                'type_bien'   => $type,
                'adresse'     => $adresse,
                'ville'       => $ville,
                'superficie'  => $surface,
                'description' => "$titre à $ville. Bien situé, proche de tous les commerces et transports.",
            ]);

            // Détails dans la table du type : appartement(), villa() ou maison()
            $bien->{$type}()->create(array_merge(
                ['chambres' => $chambres, 'salles_de_bain' => $sdb],
                $details
            ));

            Annonce::create([
                'bien_id'          => $bien->id,
                'titre'            => $titre,
                'type_transaction' => $transaction,
                'prix'             => $prix,
                'statut'           => 'disponible',
            ]);
        }
    }
}