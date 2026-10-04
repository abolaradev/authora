<?php

namespace Abolaradev\Authora;

use Illuminate\Support\ServiceProvider;
use Abolaradev\Authora\Commands\AuthoraCommand;
use Livewire\Livewire;
use Illuminate\Support\Facades\Blade;

class AuthoraServiceProvider extends ServiceProvider
{
     /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge package configuration.
        $this->mergeConfigFrom(
            __DIR__.'/../config/authora.php',
            'authora'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Register the package views as a Livewire namespace
        Livewire::addNamespace(
            namespace: 'authora',
            viewPath: __DIR__ . '/../resources/views/livewire',
        );

        // Register the package Blade components namespace
        Blade::componentNamespace(__DIR__ . '/../resources/views/components','authora');

        // Load the package routes
        $this->loadRoutesFrom(__DIR__.'/../routes/authora.php');

        // Load the package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'authora');

        // Publishing is only available when running from the console
        if (! $this->app->runningInConsole()) {
            return;
        }

        // Publish the package configuration file
        $this->publishes([
            __DIR__.'/../config/authora.php' => config_path('authora.php'),
        ], ['authora', 'authora-config']);

        // Publish the package views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/authora'),
        ], ['authora', 'authora-views']);

        // Publish the package assets
        $this->publishes([
            __DIR__.'/../resources/dist' => public_path('vendor/authora'),
        ], ['authora', 'authora-assets']);

        // Publish the package migrations
        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], ['authora', 'authora-migrations']);

        // Register the package Artisan commands
        $this->commands([
            AuthoraCommand::class
        ]);
    }
}
