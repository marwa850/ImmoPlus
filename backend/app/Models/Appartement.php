<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appartement extends Model
{
    protected $table = 'appartements';

    public $timestamps = false;

    protected $primaryKey = 'bien_id';

    public $incrementing = false;

    protected $fillable = [
        'bien_id',
        'chambres',
        'salles_de_bain',
        'etage',
        'ascenseur',
        'balcon',
    ];

    public function bien()
    {
        return $this->belongsTo(Bien::class, 'bien_id');
    }
}