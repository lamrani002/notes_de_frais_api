<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ExpenseNoteService;
use App\Http\Requests\ExpenseNoteRequest;
use Exception;
use Illuminate\Http\Request;

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
     * Récupère toutes les notes de frais.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $notes = $this->expenseNoteService->getAllNotes();
            return response()->json($notes, 200);
        } catch (Exception $e) {
            return response()->json(['error' => "Erreur lors de la récupération des notes : " . $e->getMessage()], 500);
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
            return response()->json(['error' => $e->getMessage()], 404);
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
            $note = $this->expenseNoteService->createNote($request->validated(), $request->user()->id);
            return response()->json($note, 201);
        } catch (Exception $e) {
            return response()->json(['error' =>  $e->getMessage()], 403);
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
            $note = $this->expenseNoteService->updateNote($id, $request->validated(), $request->user()->id);
            return response()->json($note, 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        } 
    }

    /**
     * Supprime une note de frais.
     *
     * @param int $id L'identifiant de la note de frais à supprimer.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $this->expenseNoteService->deleteNote($id, $request->user()->id);
            return response()->json(['message' => 'Note supprimée avec succès.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' =>  $e->getMessage()], 403);
        }
    }
}
