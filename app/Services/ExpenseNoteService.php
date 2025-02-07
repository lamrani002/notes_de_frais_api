<?
namespace App\Services;

use App\Models\ExpenseNote;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Service pour gérer les opérations sur les notes de frais.
 */
class ExpenseNoteService
{
    /**
     * Vérifie si l'utilisateur est `id = 1`
     *
     * @param int $userId
     * @throws Exception
     */
    private function checkAdminAccess($userId)
    {
        if ($userId !== 1) {
            throw new Exception("Accès interdit. Seul l'utilisateur #1 peut gérer ces données.");
        }
    }

    /**
     * Récupère toutes les notes de frais (accessible à tous les utilisateurs).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllNotes()
    {
        try {
            return ExpenseNote::all();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des notes de frais : " . $e->getMessage());
        }
    }

    /**
     * Récupère une note de frais spécifique.
     *
     * @param int $id L'identifiant de la note de frais.
     * @return ExpenseNote
     * @throws Exception Si la note de frais est introuvable.
     */
    public function getNoteById($id)
    {
        try {
            return ExpenseNote::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new Exception("Note de frais introuvable avec l'ID : $id");
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération de la note de frais : " . $e->getMessage());
        }
    }

    /**
     * Crée une nouvelle note de frais (Réservé à user_id = 1).
     *
     * @param array $data
     * @param int $userId
     * @return array Réponse JSON
     * @throws Exception
     */
    public function createNote(array $data, $userId)
    {
        $this->checkAdminAccess($userId);

        try {
            $note = ExpenseNote::create([
                'note_date' => $data['note_date'],
                'amount' => $data['amount'],
                'type' => $data['type'],
                'registration_date' => $data['registration_date'] ?? now(),
                'user_id' => $userId,
                'company_id' => $data['company_id'],
            ]);

            return ['message' => 'Note créée avec succès', 'note' => $note];
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la création de la note de frais : " . $e->getMessage());
        }
    }

    /**
     * Met à jour une note de frais existante (Réservé à user_id = 1).
     *
     * @param int $id
     * @param array $data
     * @param int $userId
     * @return array Réponse JSON
     * @throws Exception
     */
    public function updateNote($id, array $data, $userId)
    {
        $this->checkAdminAccess($userId);

        try {
            $expenseNote = $this->getNoteById($id);
            $expenseNote->update($data);
            return ['message' => 'Note mise à jour avec succès', 'note' => $expenseNote];
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }

    /**
     * Supprime une note de frais existante (Réservé à user_id = 1).
     *
     * @param int $id
     * @param int $userId
     * @return array Réponse JSON
     * @throws Exception
     */
    public function deleteNote($id, $userId)
    {
        $this->checkAdminAccess($userId);

        try {
            $expenseNote = $this->getNoteById($id);
            $expenseNote->delete();
            return ['message' => 'Note supprimée avec succès'];
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la suppression : " . $e->getMessage());
        }
    }
}
