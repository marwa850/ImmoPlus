<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratLocation extends Model
{
    protected $table = 'contrats_location';

    public $timestamps = false;

    protected $fillable = [
        'annonce_id',
        'locataire_id',
        'date_debut',
        'date_fin',
        'montant_mensuel',
        'caution',
        'statut',
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'annonce_id');
    }

    public function locataire()
    {
        return $this->belongsTo(User::class, 'locataire_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'contrat_id');
    }
}