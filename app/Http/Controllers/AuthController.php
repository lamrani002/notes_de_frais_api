<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Inscription d'un nouvel utilisateur.
     */
    public function register(RegisterRequest $request)
    {
        try {
            $data = $this->authService->register($request->validated());
            return response()->json($data, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Connexion d'un utilisateur.
     */
    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService->login($request->validated());
            return response()->json($data, 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Déconnexion de l'utilisateur.
     */
    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request->user());
            return response()->json(['message' => 'Déconnexion réussie.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
