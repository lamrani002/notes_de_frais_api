<?php 

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ExpenseNoteService;
use App\Models\ExpenseNote;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test unitaire pour le service ExpenseNoteService.
 */
class ExpenseNoteServiceTest extends TestCase
{
    use RefreshDatabase;

   
    protected $expenseNoteService;
    protected $adminUser;
    protected $normalUser;

    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Création des utilisateurs
        $this->adminUser = User::factory()->create(['id' => 1]); 
        $this->normalUser = User::factory()->create(); 
        
        $this->expenseNoteService = new ExpenseNoteService();
    }

    /**
     * Teste la récupération de toutes les notes de frais.
     */
    public function test_get_all_notes()
    {
        ExpenseNote::factory()->count(3)->create(['user_id' => 1]);

        $notes = $this->expenseNoteService->getAllNotes();
        $this->assertCount(3, $notes);
    }

    /**
     * Teste qu'un utilisateur non admin peut récupérer les notes de frais (lecture seule).
     */
    public function test_normal_user_can_only_view_notes()
    {
        ExpenseNote::factory()->count(3)->create(['user_id' => 1]);

        $this->actingAs($this->normalUser);
        $notes = $this->expenseNoteService->getAllNotes();

        $this->assertCount(3, $notes);
    }

    /**
     * Teste la création d'une note de frais par un utilisateur admin.
     */
    public function test_admin_can_create_note()
    {
        $company = Company::factory()->create();

        $data = [
            'note_date' => '2023-10-01',
            'amount' => 100.50,
            'type' => 'essence',
            'company_id' => $company->id,
        ];

        $this->actingAs($this->adminUser);
        $note = $this->expenseNoteService->createNote($data, $this->adminUser->id);

        $this->assertDatabaseHas('expense_notes', ['type' => 'essence']);
    }

    /**
     * Teste qu'un utilisateur normal ne peut pas créer une note de frais.
     */
    public function test_normal_user_cannot_create_note()
    {
        $company = Company::factory()->create();

        $data = [
            'note_date' => '2023-10-01',
            'amount' => 100.50,
            'type' => 'essence',
            'company_id' => $company->id,
        ];

        $this->actingAs($this->normalUser);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Accès interdit. Seul l'utilisateur #1 peut gérer ces données.");

        $this->expenseNoteService->createNote($data, $this->normalUser->id);
    }

    /**
     * Teste qu'un utilisateur admin peut mettre à jour une note de frais.
     */
    public function test_admin_can_update_note()
    {
        $note = ExpenseNote::factory()->create(['user_id' => 1]);

        $data = ['amount' => 200.00, 'type' => 'péage'];

        $this->actingAs($this->adminUser);
        $updatedNote = $this->expenseNoteService->updateNote($note->id, $data, $this->adminUser->id);
        
        $this->assertIsArray($updatedNote);
        $this->assertArrayHasKey('message', $updatedNote);
        $this->assertArrayHasKey('note', $updatedNote);
        $this->assertInstanceOf(ExpenseNote::class, $updatedNote['note']);
        $this->assertEquals(200.00, $updatedNote['note']->amount);
        $this->assertEquals('péage', $updatedNote['note']->type);
        
    }

    /**
     * Teste qu'un utilisateur non admin ne peut pas mettre à jour une note de frais.
     */
    public function test_normal_user_cannot_update_note()
    {
        $note = ExpenseNote::factory()->create(['user_id' => 1]);

        $data = ['amount' => 200.00, 'type' => 'péage'];

        $this->actingAs($this->normalUser);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Accès interdit. Seul l'utilisateur #1 peut gérer ces données.");

        $this->expenseNoteService->updateNote($note->id, $data, $this->normalUser->id);
    }

    /**
     * Teste qu'un utilisateur admin peut supprimer une note de frais.
     */
    public function test_admin_can_delete_note()
    {
        $note = ExpenseNote::factory()->create(['user_id' => 1]);

        $this->actingAs($this->adminUser);
        $this->expenseNoteService->deleteNote($note->id, $this->adminUser->id);

        $this->assertDatabaseMissing('expense_notes', ['id' => $note->id]);
    }

    /**
     * Teste qu'un utilisateur normal ne peut pas supprimer une note de frais.
     */
    public function test_normal_user_cannot_delete_note()
    {
        $note = ExpenseNote::factory()->create(['user_id' => 1]);

        $this->actingAs($this->normalUser);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Accès interdit. Seul l'utilisateur #1 peut gérer ces données.");

        $this->expenseNoteService->deleteNote($note->id, $this->normalUser->id);
    }

    /**
     * Teste la suppression d'une note de frais inexistante (doit générer une exception).
     *
     * @return void
     */
    public function test_delete_note_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expenseNoteService->deleteNote(999, $this->adminUser->id);
    }
}
