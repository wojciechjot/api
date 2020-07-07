<?php

namespace Tests\Feature\Auth;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepositoryInterface $userRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->app->make(UserRepositoryInterface::class);
    }

    /** @test */
    public function canRemindPassword()
    {
        $email = "test@email.com";

        $registerResponse = $this->postJson('/api/register', [
            "name" => "Jan",
            "email" => $email,
            "password" => "password123",
            "password_confirmation" => "password123"
        ]);

        $registerResponse->assertStatus(201);

        $response = $this->postJson('/api/remind-password', [
            "email" => $email,
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function canSetPassword()
    {
        $email = "test@email.com";

        $registerResponse = $this->postJson('/api/register', [
            "name" => "Jan",
            "email" => $email,
            "password" => "password123",
            "password_confirmation" => "password123"
        ]);

        $registerResponse->assertStatus(201);

        $user = $this->userRepository->findOneByEmail($email);

        $enableResponse = $this->postJson('/api/enable-user/' . $user->confirmation_token);

        $enableResponse->assertStatus(201);

        $response = $this->postJson('/api/set-password', [
            "token" => $user->confirmation_token,
            "password" => "password123",
            "password_confirmation" => "password123",
        ]);

        $response->assertStatus(201);
    }
}
