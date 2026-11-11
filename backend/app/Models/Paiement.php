<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiements';

    public $timestamps = false;

    protected $fillable = [
        'contrat_id',
        'montant',
        'date_echeance',
        'date_paiement',
        'mode_paiement',
        'statut',
        'reference',
    ];

    public function contrat()
    {
        return $this->belongsTo(ContratLocation::class, 'contrat_id');
    }
}