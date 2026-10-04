<?php

namespace Workbench\App\Console\Components;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Workbench\App\Abolaradev\HasComponentMaker;

#[Signature('livewire:layout {layout?} {--w | workbench : Create the livewire layout in the Workbench environment}')]
#[Description('Create a new Livewire layout')]
class MakeLivewireLayoutCommand extends Command
{
    use HasComponentMaker;

    protected function subDirectory()
    {
        return 'layouts';
    }

    protected function outputMessages()
    {
        return [
            'success' => 'Livewire layout Created',
            'fail' => 'Livewire layout is exist'
        ];
    }

    protected function template()
    {
        return <<<'Blade'
        <!DOCTYPE html>
        <html lang='{{ str_replace('_', '-', app()->getLocale()) }}'>
            <head>
                <meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>

                <title>{{ $title ?? 'skeleton' }}</title>

                @livewireStyles
            </head>
            <body>
                {{ $slot }}

                @livewireScripts

            </body>
        </html>
        Blade;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $layout=$this->argument('layout');
        $layout = empty($layout) ? 'app' : $layout;

        $this->setPath($layout)
             ->create();
    }
}
