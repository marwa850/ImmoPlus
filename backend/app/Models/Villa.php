<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Villa extends Model
{
    protected $table = 'villas';

    public $timestamps = false;

    protected $primaryKey = 'bien_id';

    public $incrementing = false;

    protected $fillable = [
        'bien_id',
        'chambres',
        'salles_de_bain',
        'nombre_etages',
        'piscine',
        'jardin',
        'garage',
    ];

    public function bien()
    {
        return $this->belongsTo(Bien::class, 'bien_id');
    }
}