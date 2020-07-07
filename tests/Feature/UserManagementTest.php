<?php

namespace Tests\Feature;

use App\Repositories\Post\PostRepositoryInterface;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected PostRepositoryInterface $postRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->postRepository = $this->app->make(PostRepositoryInterface::class);
    }

    /** @test */
    public function getCurrentUser()
    {
        $user = Passport::actingAs(factory(User::class)->create());

        $response = $this->actingAs($user)->getJson('/api/users/me');

        $response->assertStatus(200);
    }

    /** @test */
    public function getAllPostsForUser()
    {
        $user = Passport::actingAs(factory(User::class)->create());

        $createResponse = $this->actingAs($user)->postJson('/api/posts', [
            "title" =>  "post testowy",
            "content" =>  "treść posta testowego",
            "publication_date" =>  "2020-05-12",
            "beginning" =>  "2020-05-12",
            "end" =>  "2020-05-12"
        ]);

        $this->assertCount(1, $this->postRepository->findAll());

        $createResponse->assertStatus(200);

        $response = $this->actingAs($user)->getJson('/api/users/' . $user->id . '/posts');

        $response->assertStatus(200);
    }
}
