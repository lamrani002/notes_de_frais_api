<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ExpenseNote;
use App\Models\Company;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseNoteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $normalUser;
    protected $company;

    protected function setUp(): void
    {
        parent::setUp();

        // Création des utilisateurs
        $this->adminUser = User::factory()->create(['id' => 1]);
        $this->normalUser = User::factory()->create();

        // Création d'une company
        $this->company = Company::factory()->create();
    }

    /**
     * Teste la récupération de toutes les notes de frais.
     */
    public function test_get_all_notes()
    {
        ExpenseNote::factory()->count(3)->create(['user_id' => 1, 'company_id' => $this->company->id]);

        Sanctum::actingAs($this->normalUser);
        $response = $this->getJson('/api/expense-notes');

        $response->assertStatus(200)->assertJsonCount(3);
    }

    /**
     * Teste qu'un utilisateur peut récupérer une note spécifique.
     */
    public function test_get_note_by_id()
    {
        $note = ExpenseNote::factory()->create(['user_id' => 1, 'company_id' => $this->company->id]);

        Sanctum::actingAs($this->normalUser);
        $response = $this->getJson("/api/expense-notes/{$note->id}");

        $response->assertStatus(200)->assertJson(['id' => $note->id]);
    }

    /**
     * Teste qu'un admin peut créer une note de frais.
     */
    public function test_admin_can_create_note()
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->postJson('/api/expense-notes', [
            'note_date' => '2023-10-01',
            'amount' => 100.50,
            'type' => 'essence',
            'company_id' => $this->company->id,
        ]);

        $response->assertStatus(201)->assertJson(['note' => ['type' => 'essence']]);

        $this->assertDatabaseHas('expense_notes', ['amount' => 100.50]);
    }

    /**
     * Teste qu'un utilisateur normal ne peut pas créer une note de frais.
     */
    public function test_normal_user_cannot_create_note()
    {
        Sanctum::actingAs($this->normalUser);

        $response = $this->postJson('/api/expense-notes', [
            'note_date' => '2023-10-01',
            'amount' => 100.50,
            'type' => 'essence',
            'company_id' => $this->company->id,
        ]);

        $response->assertStatus(403);
    }

    /**
     * Teste qu'un admin peut modifier une note de frais.
     */
    public function test_admin_can_update_note()
    {
        $note = ExpenseNote::factory()->create(['user_id' => 1, 'company_id' => $this->company->id]);

        Sanctum::actingAs($this->adminUser);
        $response = $this->putJson("/api/expense-notes/{$note->id}", [
            'amount' => 200.00,
            'type' => 'péage',
        ]);

        $response->assertStatus(200)->assertJson(['note' => ['amount' => 200.00, 'type' => 'péage']]);

        $this->assertDatabaseHas('expense_notes', ['amount' => 200.00]);
    }

    /**
     * Teste qu'un utilisateur normal ne peut pas modifier une note de frais.
     */
    public function test_normal_user_cannot_update_note()
    {
        $note = ExpenseNote::factory()->create(['user_id' => 1, 'company_id' => $this->company->id]);

        Sanctum::actingAs($this->normalUser);
        $response = $this->putJson("/api/expense-notes/{$note->id}", [
            'amount' => 200.00,
        ]);

        $response->assertStatus(403);
    }

    /**
     * Teste qu'un admin peut supprimer une note de frais.
     */
    public function test_admin_can_delete_note()
    {
        $note = ExpenseNote::factory()->create(['user_id' => 1, 'company_id' => $this->company->id]);

        Sanctum::actingAs($this->adminUser);
        $response = $this->deleteJson("/api/expense-notes/{$note->id}");

        $response->assertStatus(200)->assertJson(['message' => 'Note supprimée avec succès.']);

        $this->assertDatabaseMissing('expense_notes', ['id' => $note->id]);
    }

    /**
     * Teste qu'un utilisateur normal ne peut pas supprimer une note de frais.
     */
    public function test_normal_user_cannot_delete_note()
    {
        $note = ExpenseNote::factory()->create(['user_id' => 1, 'company_id' => $this->company->id]);

        Sanctum::actingAs($this->normalUser);
        $response = $this->deleteJson("/api/expense-notes/{$note->id}");

        $response->assertStatus(403);
    }

    /**
     * Teste qu'un utilisateur non authentifié ne peut pas modifier une note de frais.
     */
    public function test_guest_cannot_update_note()
    {
        $note = ExpenseNote::factory()->create(['user_id' => 1, 'company_id' => $this->company->id]);

        $response = $this->putJson("/api/expense-notes/{$note->id}", [
            'amount' => 200.00,
        ]);

        $response->assertStatus(401);
    }
}
