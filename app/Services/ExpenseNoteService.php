<?
namespace App\Services;

use App\Models\ExpenseNote;
use Exception;

/**
 * Service pour gérer les opérations sur les notes de frais.
 */
class ExpenseNoteService
{
    /**
     * L'identifiant de l'utilisateur (avec ID= 1).
     *
     * @var int
     */
    protected $userId;

    /**
     * Constructeur du service ExpenseNoteService.
     * TODO: Récupérer dynamiquement l'utilisateur authentifié.
     */
    public function __construct()
    {
        $this->userId = 1; 
    }

    /**
     * Récupère toutes les notes de frais de l'utilisateur (ID =1).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws Exception Si une erreur survient.
     */
    public function getAllNotes()
    {
        try {
            return ExpenseNote::where('user_id', $this->userId)->get();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des notes de frais.");
        }
    }

    /**
     * Récupère note de frais spécifique par ID.
     *
     * @param int $id L'identifiant de la note de frais.
     * @return ExpenseNote
     * @throws ModelNotFoundException Si la note de frais est introuvable.
     */
    public function getNoteById($id)
    {
        return ExpenseNote::where('user_id', $this->userId)->findOrFail($id);  
    }

    /**
     * Crée une nouvelle note de frais pour l'utilisateur actuel.
     *
     * @param array $data Les données de la nouvelle note de frais.
     * @return ExpenseNote La note de frais créée.
     * @throws Exception Si une erreur survient lors de la création.
     */
    public function createNote(array $data)
    {
        try {
            return ExpenseNote::create([
                'note_date' => $data['note_date'],
                'amount' => $data['amount'],
                'type' => $data['type'],
                'registration_date' => $data['registration_date'] ?? now(),
                'user_id' => $this->userId, // Sécurisé
                'company_id' => $data['company_id'],
            ]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la création de la note de frais.");
        }
    }

    /**
     * Met à jour une note de frais existante.
     *
     * @param int $id L'identifiant de la note de frais.
     * @param array $data Les nouvelles données de la note de frais.
     * @return ExpenseNote La note de frais mise à jour.
     * @throws Exception Si une erreur survient lors de la mise à jour.
     */
    public function updateNote($id, array $data)
    {
        try {
            $expenseNote = ExpenseNote::where('user_id', $this->userId)->findOrFail($id);
            $expenseNote->update($data);
            return $expenseNote;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la mise à jour de la note de frais.");
        }
    }

    /**
     * Supprime une note de frais existante.
     *
     * @param int $id L'identifiant de la note de frais à supprimer.
     * @return array Message de confirmation.
     * @throws Exception Si une erreur survient lors de la suppression.
     */
    public function deleteNote($id)
    {
        try {
            $expenseNote = ExpenseNote::where('user_id', $this->userId)->findOrFail($id);
            $expenseNote->delete();
            return ['message' => 'Note supprimée'];
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la suppression de la note de frais.");
        }
    }
}
