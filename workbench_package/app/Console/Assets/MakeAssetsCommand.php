<?php

namespace Workbench\App\Console\Assets;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use function Laravel\Prompts\alert;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\warning;
use function Orchestra\Sidekick\working_path;

#[Signature('make:assets {--except=?}')]
#[Description('Create package assets')]
class MakeAssetsCommand extends Command
{
     /**
     * Create the package asset directories.
     *
     * Creates the default asset directories inside the working path.
     * Existing directories will not be recreated.
     *
     * @param array $except Asset directories that should not be created.
     *
     * @return self
     */
    public function make(array $except = []): self
    {
        $dist = working_path('resources\\dist\\');

        $assets = Arr::exceptValues(
            ['js', 'css', 'fonts', 'images'],
            $except
        );

        $assetsPaths = Arr::map($assets, function ($value) use ($dist) {
            return $dist . $value;
        });

        $assetsPaths = array_combine($assets, $assetsPaths);

        if (count($assetsPaths) > 0) {
            intro('Creating asset directories...');

            foreach ($assetsPaths as $key => $value) {
                if (!is_dir($value)) {
                    mkdir($value, 0775, true);

                    note("$key asset created: [$value]");
                }else{
                    warning("$key asset already exists: [$value]");
                }
            }

            info('Asset directory setup completed.');
        } else {
            alert('No asset directories to create.');
        }

        return new $this;
    }

     /**
     * Create and append the package asset helper function to the helpers file.
     *
     * @return void
     */
    public static function createhelperAsset() :void
    {
        $helper=working_path('src\\helpers\\helpers.php');
        $helperFile=file_get_contents($helper);
        
        if(Str::contains($helperFile,'package_asset')==false){
            $assetHelper = <<<'php'

            function :package_snake_asset(string $path)
            {
                $filePath = public_path("vendor/skeleton/$path");

                return file_exists($filePath)
                       ? asset("vendor/skeleton/$path")
                       : url()->query("assets/$path" ,['ver'=>time()]);
            }

            php;
         
            file_put_contents($helper,$assetHelper,FILE_APPEND);

            outro("Also package_asset() helper added.");
            note("at: [$helper]");
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $except = explode(',', $this->option('except'));

        $this->make($except)
             ->createHelperAsset();
    }
}
