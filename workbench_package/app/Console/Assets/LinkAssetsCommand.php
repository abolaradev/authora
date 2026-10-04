<?php

namespace Workbench\App\Console\Assets;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use function Laravel\Prompts\alert;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Orchestra\Sidekick\working_path;
use function Orchestra\Testbench\workbench_path;

#[Signature('assets:link')]
#[Description('Link package assets to the public directory')]
class LinkAssetsCommand extends Command
{
    
     /**
     * Create a junction link for the package assets.
     *
     * Creates a Windows junction from the Workbench public directory
     * to the package asset directory.
     *
     */
    public function handle()
    {
        $target = working_path('resources\\dist');

        if (is_dir($target)) {
            $link = workbench_path('public');

            exec("mklink /J \"$link\" \"$target\"") ? info("Assets linked successfully: [$link]")
                                                    : error("Failed to link assets: [$link]");
                                                    
        }else {
            alert("No asset directory exists to link: [$target]");
        }
    }
}
