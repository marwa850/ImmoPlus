<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // On cherche le rôle admin créé par RoleSeeder
        $roleAdmin = Role::where('nom', 'admin')->first();

        // Création du compte administrateur
        User::create([
            'nom'          => 'Admin',
            'prenom'       => 'ImmoPlus',
            'email'        => 'admin@immoplus.com',
            'telephone'    => '0600000000',
            'mot_de_passe' => 'admin123', // haché automatiquement grâce au cast 'hashed'
            'role_id'      => $roleAdmin->id,
            'actif'        => true,
        ]);
    }
}