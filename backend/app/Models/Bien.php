<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bien extends Model
{
    protected $table = 'biens';

    protected $fillable = [
        'proprietaire_id',
        'type_bien',
        'adresse',
        'ville',
        'superficie',
        'description',
    ];

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    public function appartement()
    {
        return $this->hasOne(Appartement::class, 'bien_id');
    }

    public function villa()
    {
        return $this->hasOne(Villa::class, 'bien_id');
    }

    public function maison()
    {
        return $this->hasOne(Maison::class, 'bien_id');
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'bien_id');
    }

    public function images()
    {
        return $this->hasMany(ImageBien::class, 'bien_id');
    }
}