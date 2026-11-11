<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visite extends Model
{
    protected $table = 'visites';

    public $timestamps = false;

    protected $fillable = [
        'utilisateur_id',
        'annonce_id',
        'date_visite',
        'statut',
        'commentaire',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'annonce_id');
    }
}