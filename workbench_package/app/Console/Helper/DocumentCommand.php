<?php

namespace Workbench\App\Console\Helper;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use function Laravel\Prompts\alert;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Orchestra\Sidekick\working_path;

#[Signature('make:docs')]
#[Description('Create a directory named `docs` at the package root, containing screenshots and other documentation related to the package')]
class DocumentCommand extends Command
{
    /**
     * Creating the `docs` directory at the package root.
     */
    public function handle()
    {
        $docs = working_path('docs');

        if(! is_dir($docs)){
            mkdir($docs);
            info('docs directory created');
        }else{
            alert('docs directory is exist');
        }

        note("at: [$docs]");
    }
}
