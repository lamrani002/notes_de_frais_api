<?php 

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ExpenseNoteService;
use App\Models\ExpenseNote;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test unitaire pour le service ExpenseNoteService.
 */
class ExpenseNoteServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Service ExpenseNoteService.
     *
     * @var ExpenseNoteService
     */
    protected $expenseNoteService;


    /**
     * Instance de l'utilisateur pour les tests.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Instance de l'entreprise pour les tests.
     *
     * @var \App\Models\Company
     */
    protected $company;

    /**
     * Initialise le service
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Crée un utilisateur avec id = 1 par défaut pour les tests 
        $this->user = \App\Models\User::factory()->create([
            'id' => 1,
        ]);

        // Crée une entreprise par défaut pour les tests
        $this->company = \App\Models\Company::factory()->create([
            'id' => 1,
        ]);

        $this->expenseNoteService = new ExpenseNoteService();
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
            'company_id' => $this->company->id
        ]);
        

        $notes = $this->expenseNoteService->getAllNotes();
        $this->assertCount(3, $notes);
    }

    /**
     * Teste la récupération d'une note de frais existante par son ID.
     *
     * @return void
     */
    public function test_get_note_by_id()
    {
        $note = ExpenseNote::factory()->create(['user_id' => 1]);

        $foundNote = $this->expenseNoteService->getNoteById($note->id);
        $this->assertEquals($note->id, $foundNote->id);
    }

    /**
     * Teste la récupération d'une note de frais inexistante (exception).
     *
     * @return void
     */
    public function test_get_note_by_id_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expenseNoteService->getNoteById(999);
    }

    /**
     * Teste la création d'une nouvelle note de frais.
     *
     * @return void
     */
    public function test_create_note()
    {
        $data = [
            'note_date' => '2023-10-01',
            'amount' => 100.50,
            'type' => 'essence',
            'registration_date' => '2023-10-01',
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
        ];

        $note = $this->expenseNoteService->createNote($data);
        $this->assertDatabaseHas('expense_notes', [
            'id' => $note->id,
            'type' => 'essence',
        ]);
    }

    /**
     * Teste la validation des données lors de la création d'une note de frais invalide.
     *  Date incorrecte, Valeur négative (erreur), Type non autorisé, ID inexistant
     * @return void
     */
    public function test_create_note_validation_error()
    {
        $this->expectException(\Exception::class);

        $data = [
            'note_date' => 'invalid-date',
            'amount' => -100, 
            'type' => 'invalide', 
            'company_id' => 999, 
        ];

        $this->expenseNoteService->createNote($data);
    }

    /**
     * Teste la mise à jour d'une note de frais existante.
     *
     * @return void
     */
    public function test_update_note()
    {
        $note = ExpenseNote::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id
        ]);

        $data = [
            'amount' => 200.00,
            'type' => 'peage',
        ];

        $updatedNote = $this->expenseNoteService->updateNote($note->id, $data);

        $this->assertEquals(200.00, $updatedNote->amount);
        $this->assertEquals('peage', $updatedNote->type);
    }

    /**
     * Teste la mise à jour d'une note de frais inexistante (doit générer une exception).
     *
     * @return void
     */
    public function test_update_note_not_found()
    {
        $this->expectException(\Exception::class);

        $data = ['amount' => 250];
        $this->expenseNoteService->updateNote(999, $data);
    }

    /**
     * Teste la suppression d'une note de frais existante.
     *
     * @return void
     */
    public function test_delete_note()
    {
        $note = ExpenseNote::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id
        ]);
        
        $this->expenseNoteService->deleteNote($note->id);
        $this->assertDatabaseMissing('expense_notes', ['id' => $note->id]);
    }

    /**
     * Teste la suppression d'une note de frais inexistante (doit générer une exception).
     *
     * @return void
     */
    public function test_delete_note_not_found()
    {
        $this->expectException(\Exception::class);
        $this->expenseNoteService->deleteNote(999);
    }
}
