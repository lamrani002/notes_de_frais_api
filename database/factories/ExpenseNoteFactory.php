<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExpenseNote>
 */
class ExpenseNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'note_date' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'type' => $this->faker->randomElement(['essence', 'péage', 'repas', 'conférence']),
    
        ];
    }
}
