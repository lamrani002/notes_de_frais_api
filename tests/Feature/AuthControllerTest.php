<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\RateLimiter;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste l'enregistrement d'un utilisateur via l'API.
     */
    public function test_user_can_register_via_api()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['token']);
    }

    /**
     * Teste la connexion d'un utilisateur via l'API.
     */
    public function test_user_can_login_via_api()
    {
        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    /**
     * Teste la connexion avec des informations incorrectes.
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['error' => 'Les informations de connexion sont incorrectes.']);
    }

    /**
     * Teste la déconnexion via l'API.
     */
    public function test_user_can_logout_via_api()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Déconnexion réussie.']);
    }

    /**
     * Teste la déconnexion sans être authentifié.
     */
    public function test_user_cannot_logout_without_authentication()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }

    /************* Test niveau sécurité *************/

    /**
     * Teste que les tentatives de connexion répétées sont limitées.
     */
    public function test_login_is_rate_limited()
    {
        RateLimiter::clear('login:wrong@example.com');
    
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', [
                'email' => 'wrong@example.com',
                'password' => 'wrongpassword',
            ]);
        }
    
        // 6ème tentative doit échouer avec un status 429 (Too Many Requests)
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);
    
        $response->assertStatus(429);
    }

    /**
     * Teste qu'un utilisateur ne peut pas s'inscrire avec un email éxistant.
     */
    public function test_user_cannot_register_with_existing_email()
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Vérifier que la requête échoue avec un statut 422
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }


    /**
     * Teste qu'un utilisateur non authentifié ne peut pas accéder aux routes protégées.
     */
    public function test_guest_cannot_access_protected_routes()
    {
        $routes = [
            'GET' => '/api/expense-notes',
            'POST' => '/api/expense-notes',
            'PUT' => '/api/expense-notes/1',
            'DELETE' => '/api/expense-notes/1',
        ];

        foreach ($routes as $method => $route) {
            $response = $this->json($method, $route);
            $response->assertStatus(401);
        }
    }

    
}
