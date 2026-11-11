<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    protected $table = 'annonces';

    protected $fillable = [
        'bien_id',
        'titre',
        'type_transaction',
        'prix',
        'statut',
    ];

    // Une annonce concerne un bien (ville, adresse, superficie...)
    public function bien()
    {
        return $this->belongsTo(Bien::class, 'bien_id');
    }

    public function visites()
    {
        return $this->hasMany(Visite::class, 'annonce_id');
    }

    public function offres()
    {
        return $this->hasMany(Offre::class, 'annonce_id');
    }

    public function favoris()
    {
        return $this->hasMany(Favori::class, 'annonce_id');
    }
}