<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user (Editeur de Tech Horizons)
        User::create([
            'name' => 'Editeur de Tech Horizons',
            'email' => 'editeur@techhorizons.com',
            'password' => Hash::make('TechAdmin2024!'),
            'role' => User::ROLE_ADMIN
        ]);

        // Run theme seeder
        $this->call([
            ThemeSeeder::class,
        ]);
    }
}
