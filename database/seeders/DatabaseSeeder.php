<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\ExpenseNote;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Création de l'utilisateur ADMIN (user_id = 1)
        $user = User::factory()->create([
            'id' => 1,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Création de 2 utilisateurs normaux (peuvent seulement lire)
        User::factory(2)->create();

        // Création de 3 entreprises
        $companies = Company::factory(3)->create();

        // Création de 10 notes de frais EXCLUSIVEMENT pour user_id = 1
        for ($i = 0; $i < 10; $i++) {
            ExpenseNote::factory()->create([
                'user_id' => $user->id,
                'company_id' => $companies->random()->id,
            ]);
        }
    }
}
