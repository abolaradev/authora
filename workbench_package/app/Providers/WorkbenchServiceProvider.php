<?php

namespace Workbench\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Workbench\App\Console\Assets\LinkAssetsCommand;
use Workbench\App\Console\Assets\MakeAssetsCommand;
use Workbench\App\Console\Assets\TailwindCommand;
use Workbench\App\Console\Components\MakeBladeComponentCommand;
use Workbench\App\Console\Components\MakeLivewireComponentCommand;
use Workbench\App\Console\Components\MakeLivewireLayoutCommand;
use Workbench\App\Console\Helper\DocumentCommand;
use Workbench\App\Console\Helper\HelperCommand;

use function Orchestra\Testbench\workbench_path;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
           /**
         * Serve Workbench assets dynamically.
         *
         * Resolves the requested asset path, determines its MIME type,
         * and returns the file response if the asset exists.
         */
         Route::get("assets/{path}",function(string $path){
            $assetUrl=workbench_path("public\\$path");
            
            $mimeTypes = [
                'css'   => 'text/css',
                'js'    => 'application/javascript',
                'json'  => 'application/json',
                'svg'   => 'image/svg+xml',
                'png'   => 'image/png',
                'jpg'   => 'image/jpeg',
                'jpeg'  => 'image/jpeg',
                'webp'  => 'image/webp',
                'woff'  => 'font/woff',
                'woff2' => 'font/woff2',
            ];

            $extension = pathinfo($assetUrl, PATHINFO_EXTENSION);

            return (is_file($assetUrl)) ? response()->file($assetUrl, [
                                         'Content-Type' => $mimeTypes[$extension] ,
                                        ])
                                        : abort(404);
        })->where('path','.*'); 


        // Register the workbench Artisan commands
        $this->commands([
            HelperCommand::class,
            MakeAssetsCommand::class,
            LinkAssetsCommand::class,
            MakeBladeComponentCommand::class,
            MakeLivewireComponentCommand::class,
            MakeLivewireLayoutCommand::class,
            TailwindCommand::class,
            DocumentCommand::class
        ]);

        // Register the package Blade components namespace
        Blade::componentNamespace(__DIR__ . '/../../resources/views/components','workbench');

        //Register the package views as a Livewire namespace
        Livewire::addNamespace(
            namespace: 'workbench',
            viewPath: __DIR__ . '/../../resources/views/livewire',
        );

        // Load the workbench views
        $this->loadViewsFrom(__DIR__."/../../resources/views",'workbench');
    }
}
