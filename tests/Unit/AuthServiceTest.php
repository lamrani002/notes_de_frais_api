<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    /**
     * Teste l'enregistrement d'un nouvel utilisateur.
     */
    public function test_user_can_register()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ];

        $user = $this->authService->register($data);

        $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);
        $this->assertArrayHasKey('user', $user);
        $this->assertInstanceOf(User::class, $user['user']);
        $this->assertTrue(Hash::check('password123', $user['user']->password));
    }

    /**
     * Teste la connexion d'un utilisateur avec les bonnes informations.
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->authService->login([
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        $this->assertArrayHasKey('token', $response);
        $this->assertNotNull($response['token']);
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

        $response = $this->authService->login([
            'email' => 'johndoe@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertArrayHasKey('error', $response);
        $this->assertEquals('Les informations de connexion sont incorrectes.', $response['error']);
    }

    /**
     * Teste la déconnexion d'un utilisateur.
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $this->actingAs($user);
        $response = $this->authService->logout($user);

        $this->assertEquals(['message' => 'Déconnexion réussie'], $response);
        $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $user->id]);
    }
}
