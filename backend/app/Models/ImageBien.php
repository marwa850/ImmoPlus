<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageBien extends Model
{
    protected $table = 'images_biens';

    // La table n'a que created_at (pas updated_at), on gère donc le champ nous-mêmes
    public $timestamps = false;

    protected $fillable = [
        'bien_id',
        'chemin_image',
        'principale',
    ];

    protected function casts(): array
    {
        return [
            'principale' => 'boolean',
        ];
    }

    public function bien()
    {
        return $this->belongsTo(Bien::class, 'bien_id');
    }
}