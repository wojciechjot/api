<?php

namespace Tests\Feature;

use App\Repositories\Post\PostRepositoryInterface;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostManagementTest extends TestCase
{
    use RefreshDatabase;

    protected PostRepositoryInterface $postRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->postRepository = $this->app->make(PostRepositoryInterface::class);
    }

    /** @test */
    public function getAllPosts()
    {
        $response = $this->getJson('/api/posts');

        $response->assertStatus(200);
    }

    /** @test */
    public function createPost()
    {
        $user = Passport::actingAs(factory(User::class)->create());

        $this->createPostSnippet($user);
    }

    /** @test */
    public function updatePost()
    {
        $user = Passport::actingAs(factory(User::class)->create());

        $this->createPostSnippet($user);

        $post = $this->postRepository->findAll()->first();

        $response = $this->actingAs($user)->putJson('/api/posts/' . $post->id, [
            "title" => "zmieniony tytuł",
            "content" =>  "zmieniona treść posta testowego",
            "publication_date" =>  "2021-05-12",
            "beginning" =>  "2021-05-12",
            "end" =>  "2021-05-12"
        ]);

        $post = $this->postRepository->findAll()->first();

        $this->assertEquals('zmieniony tytuł', $post->title);
        $this->assertEquals('zmieniona treść posta testowego', $post->content);
        $this->assertEquals('2021-05-12', $post->publication_date);
        $this->assertEquals('2021-05-12', $post->beginning);
        $this->assertEquals('2021-05-12', $post->end);

        $response->assertStatus(200);
    }

    /** @test */
    public function deletePost()
    {
        $user = Passport::actingAs(factory(User::class)->create());

        $this->createPostSnippet($user);

        $post = $this->postRepository->findAll()->first();

        $response = $this->actingAs($user)->deleteJson('/api/posts/' . $post->id);

        $this->assertCount(0, $this->postRepository->findAll());
        $response->assertStatus(204);
    }

    private function createPostSnippet(Authenticatable $user): void
    {
        $response = $this->actingAs($user)->postJson('/api/posts', [
            "title" =>  "post testowy",
            "content" =>  "treść posta testowego",
            "publication_date" =>  "2020-05-12",
            "beginning" =>  "2020-05-12",
            "end" =>  "2020-05-12"
        ]);

        $this->assertCount(1, $this->postRepository->findAll());

        $response->assertStatus(200);
    }
}
