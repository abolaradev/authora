<?php

namespace Abolaradev\Authora\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\info;
use function Orchestra\Sidekick\working_path;

class AuthoraCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'authora:install';

    /**
     * The console command description.
     *
     * @var string
     */
    public $description = 'Install Authora actions into the application.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $actions = working_path('actions');
        $app = app_path('Actions');

        if(!File::isDirectory($app)){
            File::makeDirectory($app,recursive:true);
        }

        File::copyDirectory($actions,$app);

        info('Authora actions installed successfully.');
    }
}
