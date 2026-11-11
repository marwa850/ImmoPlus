<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Bien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAnnonceController extends Controller
{
    private array $relations = ['bien.images', 'bien.appartement', 'bien.villa', 'bien.maison'];

    // LISTE ADMIN : toutes les annonces, y compris les brouillons
    public function index()
    {
        return response()->json(
            Annonce::with($this->relations)->orderBy('created_at', 'desc')->paginate(10)
        );
    }

    // CRÉER une annonce (F5) : un bien + son détail + l'annonce
    public function store(Request $request)
    {
        $data = $request->validate([
            'titre'            => 'required|string|max:150',
            'type_transaction' => 'required|in:vente,location',
            'prix'             => 'required|numeric|min:1',
            'statut'           => 'nullable|in:brouillon,disponible',
            'type_bien'        => 'required|in:appartement,villa,maison',
            'adresse'          => 'required|string|max:255',
            'ville'            => 'required|string|max:80',
            'superficie'       => 'required|numeric|min:1',
            'description'      => 'nullable|string',
            'image'            => 'nullable|image|max:2048',
        ]);

        // Transaction : si une étape échoue, rien n'est enregistré
        $annonce = DB::transaction(function () use ($request, $data) {
            $bien = Bien::create([
                'type_bien'   => $data['type_bien'],
                'adresse'     => $data['adresse'],
                'ville'       => $data['ville'],
                'superficie'  => $data['superficie'],
                'description' => $data['description'] ?? null,
            ]);

            // Détails selon le type : appartement(), villa() ou maison()
            $bien->{$data['type_bien']}()->create($this->details($request, $data['type_bien']));

            // Image principale (optionnelle)
            if ($request->hasFile('image')) {
                $chemin = $request->file('image')->store('biens', 'public');
                $bien->images()->create(['chemin_image' => $chemin, 'principale' => true]);
            }

            return Annonce::create([
                'bien_id'          => $bien->id,
                'titre'            => $data['titre'],
                'type_transaction' => $data['type_transaction'],
                'prix'             => $data['prix'],
                'statut'           => $data['statut'] ?? 'disponible',
            ]);
        });

        return response()->json($annonce->load($this->relations), 201);
    }

    // MODIFIER une annonce (F6). Le type de bien ne change pas.
    public function update(Request $request, $id)
    {
        $annonce = Annonce::with('bien')->findOrFail($id);

        $data = $request->validate([
            'titre'            => 'required|string|max:150',
            'type_transaction' => 'required|in:vente,location',
            'prix'             => 'required|numeric|min:1',
            'adresse'          => 'required|string|max:255',
            'ville'            => 'required|string|max:80',
            'superficie'       => 'required|numeric|min:1',
            'description'      => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $annonce, $data) {
            $annonce->update([
                'titre'            => $data['titre'],
                'type_transaction' => $data['type_transaction'],
                'prix'             => $data['prix'],
            ]);

            $bien = $annonce->bien;
            $bien->update([
                'adresse'     => $data['adresse'],
                'ville'       => $data['ville'],
                'superficie'  => $data['superficie'],
                'description' => $data['description'] ?? null,
            ]);

            $bien->{$bien->type_bien}()->update($this->details($request, $bien->type_bien));
        });

        return response()->json($annonce->fresh($this->relations));
    }

    // SUPPRIMER une annonce (F7)
    public function destroy($id)
    {
        $annonce = Annonce::findOrFail($id);
        $bien = $annonce->bien;

        DB::transaction(function () use ($annonce, $bien) {
            $annonce->delete(); // visites, offres et favoris sont supprimés en cascade
            $bien->delete();    // détails et images aussi
        });

        return response()->json(['message' => 'Annonce supprimée']);
    }

    // CHANGER LE STATUT (F8)
    public function changerStatut(Request $request, $id)
    {
        $data = $request->validate([
            'statut' => 'required|in:brouillon,disponible,reservee,vendue,louee,archivee',
        ]);

        $annonce = Annonce::findOrFail($id);
        $annonce->update(['statut' => $data['statut']]);

        return response()->json($annonce);
    }

    // Champs spécifiques à chaque type de bien
    private function details(Request $request, string $type): array
    {
        $commun = [
            'chambres'       => $request->input('chambres', 0),
            'salles_de_bain' => $request->input('salles_de_bain', 0),
        ];

        if ($type === 'appartement') {
            return $commun + [
                'etage'     => $request->input('etage'),
                'ascenseur' => $request->boolean('ascenseur'),
                'balcon'    => $request->boolean('balcon'),
            ];
        }

        if ($type === 'villa') {
            return $commun + [
                'nombre_etages' => $request->input('nombre_etages', 1),
                'piscine'       => $request->boolean('piscine'),
                'jardin'        => $request->boolean('jardin'),
                'garage'        => $request->boolean('garage'),
            ];
        }

        // maison
        return $commun + [
            'nombre_etages' => $request->input('nombre_etages', 1),
            'jardin'        => $request->boolean('jardin'),
            'garage'        => $request->boolean('garage'),
        ];
    }
}