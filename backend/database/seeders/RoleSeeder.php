<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'nom' => 'admin',
                'description' => 'Gere toute l\'application',
            ],
            [
                'nom' => 'client',
                'description' => 'Consulte les annonces et utilise les fonctionnalites client',
            ],
        ]);
    }
}