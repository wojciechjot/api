<?php

namespace Tests\Feature\Auth;

use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepositoryInterface $userRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->app->make(UserRepositoryInterface::class);
    }

    /** @test */
    public function canRegister()
    {
        $email = "test@email.com";

        $response = $this->postJson('/api/register', [
            "name" => "Jan",
            "email" => $email,
            "password" => "password123",
            "password_confirmation" => "password123"
        ]);

        $this->assertCount(1, User::all());

        $response->assertStatus(201);
    }

    /** @test */
    public function nameIsRequired()
    {
        $response = $this->postJson('/api/register', [
            "name" => "",
            "email" => "test2@email.com",
            "password" => "password123",
            "password_confirmation" => "password123"
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function emailIsRequired()
    {
        $response = $this->postJson('/api/register', [
            "name" => "Jan",
            "email" => "",
            "password" => "password123",
            "password_confirmation" => "password123"
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function passwordIsRequired()
    {
        $response = $this->postJson('/api/register', [
            "name" => "Jan",
            "email" => "test3@email.com",
            "password" => "",
            "password_confirmation" => "password123"
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function passwordConfirmationIsRequired()
    {
        $response = $this->postJson('/api/register', [
            "name" => "Jan",
            "email" => "test4@email.com",
            "password" => "password123",
            "password_confirmation" => ""
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function canEnableUser()
    {
        $email = "test5@email.com";

        $this->postJson('/api/register', [
            "name" => "Jan",
            "email" => $email,
            "password" => "password123",
            "password_confirmation" => "password123"
        ]);

        $user = $this->userRepository->findOneByEmail($email);
        $token = $user->confirmation_token;

        $response = $this->postJson('/api/enable-user/' . $token);

        $response->assertStatus(201);
    }
}
