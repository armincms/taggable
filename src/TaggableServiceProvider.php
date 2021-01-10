<?php

namespace Armincms\Taggable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Console\Events\ArtisanStarting;
use Illuminate\Contracts\Support\DeferrableProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova as LaravelNova;
use Core\HttpSite\Events\ServingFront;

class TaggableServiceProvider extends ServiceProvider implements DeferrableProvider
{  
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Gate::policy(Tag::class, Policies\Tag::class);

        LaravelNova::resources([ 
            Nova\Tag::class,
        ]);

        app('site')->push('taggable', function($site) {
            $site->directory('tags');

            $site->pushComponent(new Components\Tag);
        });
    }

    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return [
            ServingNova::class,
            ArtisanStarting::class,
            ServingFront::class,
        ];
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
        ];
    }
}
