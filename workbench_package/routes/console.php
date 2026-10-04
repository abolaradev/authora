<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('livewire:make {component} {--w | workbench : Create the component in the Workbench environment}', function (string $component,bool $workbench){

    $this->call('make:livewire',[
        'component' => $component,
         '--workbench' => $workbench
    ]);
})->purpose('Create a new Livewire component');