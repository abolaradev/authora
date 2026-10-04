<?php

namespace Workbench\App\Console\Components;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Workbench\App\Abolaradev\HasComponentMaker;

#[Signature('make:livewire {component} {--w | workbench : Create the livewire component in the Workbench environment}')]
#[Description('Create a new Livewire component')]
class MakeLivewireComponentCommand extends Command
{
    use HasComponentMaker;

    protected function subDirectory()
    {
        return 'livewire';
    }

    protected function outputMessages()
    {
        return [
            'success' => 'Livewire Component Created',
            'fail' => 'Livewire Component is exist'
        ];
    }

    protected function template() 
    {
        $namespace = $this->createdComponentOnWorkbench() ? 'workbench'
                                                          : 'skeleton';
        return <<<Blade
        <?php
        
        use Livewire\Attributes\Layout;
        use Livewire\Component;

        new #[Layout('$namespace::layouts.app')] class extends Component
        {
            
        };
        ?>
    
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

        $this->setPath($component)
             ->create();
    }
}
