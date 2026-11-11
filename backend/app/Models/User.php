<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // HasApiTokens permet de créer le token avec createToken() à la connexion
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'mot_de_passe',
        'role_id',
        'actif',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'mot_de_passe' => 'hashed', // le mot de passe est haché automatiquement
            'actif' => 'boolean',
        ];
    }

    // Laravel doit savoir que le mot de passe est dans la colonne mot_de_passe
    public function getAuthPasswordName(): string
    {
        return 'mot_de_passe';
    }

    // Vérifie si l'utilisateur est administrateur (utilisé par le middleware)
    public function estAdmin(): bool
    {
        return $this->role && $this->role->nom === 'admin';
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function biens()
    {
        return $this->hasMany(Bien::class, 'proprietaire_id');
    }

    public function visites()
    {
        return $this->hasMany(Visite::class, 'utilisateur_id');
    }

    public function offres()
    {
        return $this->hasMany(Offre::class, 'utilisateur_id');
    }

    public function favoris()
    {
        return $this->hasMany(Favori::class, 'utilisateur_id');
    }

    public function notificationsPersonnelles()
    {
        return $this->hasMany(Notification::class, 'utilisateur_id');
    }

    public function contratsLocation()
    {
        return $this->hasMany(ContratLocation::class, 'locataire_id');
    }
}