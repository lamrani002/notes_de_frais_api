<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Enregistre un nouvel utilisateur et génère un token.
     *
     * @param array $data
     * @return array
     */
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Connecte un utilisateur et génère un token.
     *
     * @param array $credentials
     * @return array
     * @throws ValidationException
     */
    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return ['error' => 'Les informations de connexion sont incorrectes.'];
        }    

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Déconnecte l'utilisateur en supprimant tous ses tokens.
     *
     * @param User $user
     * @return void
     */
    public function logout(User $user)
    {
        $user->tokens()->delete();
        return ['message' => 'Déconnexion réussie'];
    }
}
