<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ExpenseNoteService;
use App\Http\Requests\ExpenseNoteRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Contrôleur pour gérer les notes de frais via API.
 */
class ExpenseNoteController extends Controller
{
    /**
     * Instance du service ExpenseNoteService.
     *
     * @var ExpenseNoteService
     */
    protected $expenseNoteService;

    /**
     * Constructeur du contrôleur.
     * Initialise le service ExpenseNoteService.
     *
     * @param ExpenseNoteService $expenseNoteService
     */
    public function __construct(ExpenseNoteService $expenseNoteService)
    {
        $this->expenseNoteService = $expenseNoteService;
    }

    /**
     * Récupère toutes les notes de frais de l'utilisateur.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $notes = $this->expenseNoteService->getAllNotes();
            return response()->json($notes, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des notes de frais'], 500);
        }
    }

    /**
     * Récupère une note de frais spécifique par son ID.
     *
     * @param int $id L'identifiant de la note de frais.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $note = $this->expenseNoteService->getNoteById($id);
            return response()->json($note, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Note de frais introuvable'], 404);
        }
    }

    /**
     * Crée une nouvelle note de frais.
     *
     * @param ExpenseNoteRequest $request Les données validées de la nouvelle note de frais.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExpenseNoteRequest $request)
    {
        try {
            $note = $this->expenseNoteService->createNote($request->validated());
            return response()->json($note, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création de la note de frais'], 400);
        }
    }

    /**
     * Met à jour une note de frais existante.
     *
     * @param ExpenseNoteRequest $request Les nouvelles données validées de la note de frais.
     * @param int $id L'identifiant de la note de frais.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExpenseNoteRequest $request, $id)
    {
        try {
            // Vérifier si la note existe
            $note = $this->expenseNoteService->getNoteById($id);

            // verifier la validation
            $validatedData = $request->validated();

            $updatedNote = $this->expenseNoteService->updateNote($id, $validatedData);

            return response()->json($updatedNote, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Note introuvable'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }    
    }


    /**
     * Supprime une note de frais.
     *
     * @param int $id L'identifiant de la note de frais à supprimer.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->expenseNoteService->deleteNote($id);
            return response()->json(['message' => 'Note supprimée'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression de la note de frais'], 404);
        }
    }
}
