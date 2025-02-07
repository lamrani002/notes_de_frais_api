<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ExpenseNote;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test fonctionnel du contrôleur ExpenseNoteController.
 */
class ExpenseNoteControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Instance de l'utilisateur 
     *
     * @var User
     */
    protected $user;

    /**
     * Instance de la company
     *
     * @var Company
     */
    protected $company;

    /**
     * Initialise les données avant chaque test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['id' => 1]);
        $this->company = Company::factory()->create(['id' => 1]);
    }

    /**
     * Teste la récupération de toutes les notes de frais.
     *
     * @return void
     */
    public function test_get_all_notes()
    {
        ExpenseNote::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
        ]);

        $response = $this->getJson('/api/expense-notes');
        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /**
     * Teste la récupération d'une note de frais par son ID.
     *
     * @return void
     */
    public function test_get_note_by_id()
    {
        $note = ExpenseNote::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
        ]);

        $response = $this->getJson("/api/expense-notes/{$note->id}");
        $response->assertStatus(200)
                 ->assertJson(['id' => $note->id]);
    }

    /**
     * Teste la création d'une note de frais.
     *
     * @return void
     */
    public function test_create_note()
    {
        $response = $this->postJson('/api/expense-notes', [
            'note_date' => '2023-10-01',
            'amount' => 100.50,
            'type' => 'essence',
            'registration_date' => '2023-10-01',
            'company_id' => $this->company->id,
        ]);

        $response->assertStatus(201)
                 ->assertJson(['type' => 'essence']);

        $this->assertDatabaseHas('expense_notes', ['amount' => 100.50]);
    }

    /**
     * Teste la mise à jour d'une note de frais.
     *
     * @return void
     */
    public function test_update_note()
    {
        $note = ExpenseNote::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
        ]);

        $response = $this->putJson("/api/expense-notes/{$note->id}", [
            'amount' => 200.00,
            'type' => 'péage',
            
        ]);

        $response->assertStatus(200)
                 ->assertJson(['amount' => 200.00, 'type' => 'péage']);

        $this->assertDatabaseHas('expense_notes', ['amount' => 200.00]);
    }

    /**
     * Teste la suppression d'une note de frais.
     *
     * @return void
     */
    public function test_delete_note()
    {
        $note = ExpenseNote::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
        ]);

        $response = $this->deleteJson("/api/expense-notes/{$note->id}");
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Note supprimée']);

        $this->assertDatabaseMissing('expense_notes', ['id' => $note->id]);
    }

    /**
     * Teste la validation lors de la création d'une note invalide.
     *
     * @return void
     */
    public function test_validation_for_create_note()
    {
        $response = $this->postJson('/api/expense-notes', [
            'note_date' => 'invalid-date',
            'amount' => 'not-a-number',
            'type' => 'invalid-type',
            'registration_date' => 'invalid-date',
            'company_id' => 999, // ID inexistant
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'note_date',
                     'amount',
                     'type',
                     'company_id',
                 ]);
    }

    /**
     * Teste la validation lors de la mise à jour d'une note invalide.
     *
     * @return void
     */
    public function test_validation_for_update_note()
    {
        $note = ExpenseNote::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
        ]);

        $response = $this->putJson("/api/expense-notes/{$note->id}", [
            'amount' => 'not-a-number',
            'type' => 'invalid-type',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['amount', 'type']);
    }

    /**
     * Teste la récupération d'une note inexistante.
     *
     * @return void
     */
    public function test_note_not_found()
    {
        $response = $this->getJson('/api/expense-notes/999');
        $response->assertStatus(404);
    }

    /**
     * Teste la suppression d'une note inexistante.
     *
     * @return void
     */
    public function test_delete_nonexistent_note()
    {
        $response = $this->deleteJson('/api/expense-notes/999');
        $response->assertStatus(404);
    }

    /**
     * Teste la mise à jour d'une note inexistante.
     *
     * @return void
     */
    public function test_update_nonexistent_note()
    {
        $note = ExpenseNote::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
        ]);
        
        $response = $this->putJson('/api/expense-notes/999', [
            'amount' => 200.00,
        ]);

        $response->assertStatus(404);
    }

    /**
     * Teste la récupération des notes pour un utilisateur sans notes.
     *
     * @return void
     */
    public function test_get_all_notes_for_user_without_notes()
    {
        $response = $this->getJson('/api/expense-notes');
        $response->assertStatus(200)
                 ->assertJsonCount(0);
    }
}
