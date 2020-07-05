<?php

namespace App\Providers;

use App\Http\Middleware\Cors;
use App\Http\Middleware\EnabledChecker;
use App\Policies\PostImagePolicy;
use App\Policies\PostPolicy;
use App\Post;
use App\PostImage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        PostImage::class => PostImagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(null , ['middleware' => [
            Cors::class,
            EnabledChecker::class,
        ]]);
    }
}
