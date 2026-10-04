<?php

namespace Workbench\App\Console\Helper;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use function Orchestra\Sidekick\working_path;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;

#[Signature('make:helper')]
#[Description('Create helper file')]
class HelperCommand extends Command
{

    /**
     * Indicates whether the helper file has been created.
     */
    public bool $helperCreated = false;

    /**
     * Create the helper file if it does not already exist.
     *
     * @return self
     */
    public function make(): self
    {
        $helper = working_path('src\\helpers\\helpers.php');

        if (!file_exists($helper)) {
            $path = str_replace('helpers.php', '', $helper);
            mkdir($path, 0775, true);
            file_put_contents($helper, "<?php ");
            info("Helper file created successfully.");
            $this->helperCreated = true;
        } else {
            warning("Helper file already exists.");
        }

        note("at: [$helper]");

        return $this;
    }

    /**
     * Add the helper file to Composer's autoload files configuration.
     *
     * @return self|null
     */
    public function composer(): self
    {
        if ($this->helperCreated) {
            $composer = working_path('composer.json');
            $file = file_get_contents($composer);
            $content = '"files":["src/helpers/helpers.php"]';
            $search = '"files": []';
            $replace = str_replace($search, $content, $file);
            file_put_contents($composer, $replace);
        }

         return $this;
    }

    /**
     * Regenerate Composer's autoloader.
     *
     * @return void
     */
    public function dumpAutoLoad(): void
    {
        if ($this->helperCreated) {
            spin(
                function () {
                    exec("composer dump-autoload");
                },
                "Regenerating Composer autoload..."
            );
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->make()
             ->composer()
             ->dumpAutoLoad();
    }
}
