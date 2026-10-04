<?php

namespace Workbench\App\Console\Components;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Workbench\App\Abolaradev\HasComponentMaker;

#[Signature('make:component {component} {--w | workbench : Create the blade component in the Workbench environment}')]
#[Description('Create a new Blade component')]
class MakeBladeComponentCommand extends Command
{
    use HasComponentMaker;

    protected function subDirectory()
    {
        return 'components';
    }

    protected function outputMessages()
    {
        return [
            'success' => 'Blade Component Created',
            'fail' => 'Blade Component is exist'
        ];
    }

    protected function template()
    {
        return <<<Blade
        <div>

        </div>
        Blade;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $component=$this->argument('component');

        $makeComponent = $this->setPath($component)
                              ->create();
    }
}
