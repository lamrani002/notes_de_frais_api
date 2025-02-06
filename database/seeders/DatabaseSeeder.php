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

    // Créer 1 utilisateur spécifique
    $users = User::factory(2)->create();
    $users->push(User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]));

    // Création de 3 entreprises
    $companies = Company::factory(3)->create();

    // Création de 10 notes de frais
    for ($i = 0; $i < 10; $i++) {
        ExpenseNote::factory()->create([
            'user_id' => $users->random()->id,
            'company_id' => $companies->random()->id,
        ]);
    }

    }
}
