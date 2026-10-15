<?php

namespace Abolaradev\Authora;

use Illuminate\Support\ServiceProvider;
use Abolaradev\Authora\Commands\AuthoraCommand;
use Abolaradev\Authora\Services\AuthRateLimiter;
use Abolaradev\Authora\Services\OtpBasedAuthentication;
use Abolaradev\Authora\Services\UserResetPassword;

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

        $this->app->bind(OtpBasedAuthentication::class);
        $this->app->bind(UserResetPassword::class);
        $this->app->bind(AuthRateLimiter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load the package routes
        $this->loadRoutesFrom(__DIR__.'/../routes/authora.php');

        // Load the translation files
        $this->loadTranslationsFrom(__DIR__.'/../lang');

        // Publishing is only available when running from the console
        if (! $this->app->runningInConsole()) {
            return;
        }

        // Publish the package configuration file
        $this->publishes([
            __DIR__.'/../config/authora.php' => config_path('authora.php'),
        ], ['authora', 'authora-config']);

        $this->publishes([
            __DIR__.'/../lang' => lang_path()
        ],['authora', 'authora-lang']);

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
