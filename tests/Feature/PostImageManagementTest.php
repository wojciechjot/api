<?php

namespace Tests\Feature;

use App\Post;
use App\Repositories\Post\PostRepositoryInterface;
use App\Repositories\PostImage\PostImageRepositoryInterface;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostImageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected PostRepositoryInterface $postRepository;
    protected PostImageRepositoryInterface $postImageRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->postRepository = $this->app->make(PostRepositoryInterface::class);
        $this->postImageRepository = $this->app->make(PostImageRepositoryInterface::class);
    }

    /** @test */
    public function getAllPostImages(): void
    {
        $response = $this->getJson('/api/post-images');

        $response->assertStatus(200);
    }

    /** @test */
    public function createPostImage(): void
    {
        $user = Passport::actingAs(factory(User::class)->create());

        $this->createPostSnippet($user);

        $post = $this->postRepository->findAll()->first();

        $this->createPostImageSnippet($user, $post);

    }

    /** @test */
    public function updatePostImage(): void
    {
        $user = Passport::actingAs(factory(User::class)->create());

        $this->createPostSnippet($user);

        $post = $this->postRepository->findAll()->first();

        $this->createPostImageSnippet($user, $post);

        $postImage = $this->postImageRepository->findAll()->first();

        $stub = __DIR__ . '/stubs/test.png';
        $name = Str::random(8).'.png';
        $path = sys_get_temp_dir().'/'.$name;

        copy($stub, $path);

        $file = new UploadedFile($path, $name, filesize($path), null,  true);

        $response = $this->actingAs($user)->putJson('/api/post-images/' . $postImage->id, [
            "path" => $file
        ]);

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertObjectHasAttribute('path', $content);

        $uploaded = 'storage' . DIRECTORY_SEPARATOR . 'images'. DIRECTORY_SEPARATOR . basename($content->path);
        $this->assertFileExists(public_path($uploaded));

        @unlink($uploaded);
    }

    /** @test */
    public function deletePostImage(): void
    {
        $this->withoutExceptionHandling();
        $user = Passport::actingAs(factory(User::class)->create());

        $this->createPostSnippet($user);

        $post = $this->postRepository->findAll()->first();

        $this->createPostImageSnippet($user, $post);

        $postImage = $this->postImageRepository->findAll()->first();

        $response = $this->actingAs($user)->deleteJson('/api/post-images/' . $postImage->id);
        $response->assertStatus(204);

        $this->assertCount(0, $this->postImageRepository->findAll());
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

        $response->assertStatus(200);
    }

    private function createPostImageSnippet(Authenticatable $user, Post $post): void
    {
        $stub = __DIR__ . '/stubs/test.png';
        $name = Str::random(8).'.png';
        $path = sys_get_temp_dir().'/'.$name;

        copy($stub, $path);

        $file = new UploadedFile($path, $name, filesize($path), null,  true);

        $response = $this->actingAs($user)->postJson('/api/post-images', [
            "post_id" =>  $post->id,
            "path" =>  $file
        ]);

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertObjectHasAttribute('path', $content);

        $uploaded = 'storage' . DIRECTORY_SEPARATOR . 'images'. DIRECTORY_SEPARATOR . basename($content->path);
        $this->assertFileExists(public_path($uploaded));

        @unlink($uploaded);
    }
}
