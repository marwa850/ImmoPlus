<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // L'ordre est important : le rôle admin doit exister avant le compte admin
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
        ]);
    }
}