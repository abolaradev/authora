<?php

namespace Workbench\App\Console\Assets;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\alert;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\progress;
use function Orchestra\Sidekick\working_path;

#[Signature('assets:tailwind')]
#[Description('Creating a Tailwind CSS script instead of using a CDN')]
class TailwindCommand extends Command
{
    private $script;

    const TAILWIND_CDN = 'https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4';

    
    /**
     * Getting the Tailwind Script from CDN
     *
     * @return void
     */
    public function getTailwindScriptFromCDN() :void
    {
        $this->script=Http::get(self::TAILWIND_CDN)
                          ->body();
        
    }
    

    /**
     * Retrieving Tailwind script content
     *
     * @return string
     */
    public function getScript() :string
    {
        return $this->script;
    }


    /**
     * First, it is checked to ensure that a file named `tailwind.js` does not already exist within the assets. 
     * Then, the `js` asset directory is created, and the `tailwind.js` file—containing the downloaded 
     * Tailwind script—is generated within that directory.
     *
     * @return void
     */
    public function handle() :void
    {
        $tailwind=working_path('resources\\dist\\js\\tailwind.js');

        if(!file_exists($tailwind)) {
             progress(
                label:'Tailwind CSS', 
                steps: 1 , 
                callback: fn() => $this->getTailwindScriptFromCDN() , 
                hint: 'Downloading Script...'
            );

            $this->call('make:assets',[
                '--except' => 'css,fonts,images'
            ]);

            file_put_contents($tailwind,$this->getScript());

            info('The Tailwind script created');

        }else{
            alert('Tailwind Script is exist');
        }

        note("at: [$tailwind]");
    }
}
