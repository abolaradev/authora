<?php

namespace Workbench\App\Abolaradev;

use Illuminate\Support\Str;

use function Laravel\Prompts\alert;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Orchestra\Sidekick\working_path;
use function Orchestra\Testbench\workbench_path;

trait HasComponentMaker
{   
    /**
     * Component storage path
     */
    private string $path;


    /**
     * Dot notation is converted to the directory separator.
     *
     * @param string $path It accepts a value containing dot notation and specifies the storage path for the component; 
     * the final segment of this string constitutes the component's name.
     * 
     * @param string $subDirectory The main subdirectory where components are stored.
     * 
     * @return self
     */
    public function setPath(string $path): self
    {
        $subDirectory = $this->subDirectory();
        
        $basePath = $this->createdComponentOnWorkbench() 
                          ? workbench_path("resources\\views\\$subDirectory\\") 
                          : working_path("resources\\views\\$subDirectory\\");
        

        $path = Str::replace(['.','/'],DIRECTORY_SEPARATOR,$path);
      
        $fullpath = Str::of($path)
                        ->kebab()
                        ->lower()
                        ->prepend($basePath)
                        ->finish('.blade.php');
   
        $this->path = $fullpath;

        return $this;
    }


    /**
    * Get the current component path.
    *
    * @return string
    */
    public function getPath(): string
    {
        return $this->path;
    }


    /**
     * Create the component using the appropriate template.
     *
     * Returns false if a component already exists at the target path.
     *
     * @return void
     */
    public function create() :void
    {
        $output = $this->outputMessages();
        $path = $this->getPath();

        if (file_exists($path) == false) {

            $directory = $this->getDirectory();

            if (! is_dir($directory)) {
                mkdir(
                    directory: $directory,
                    permissions: 0775,
                    recursive: true
                );
            }

            file_put_contents($path, $this->template());
            info($output['success']);
            
        }else{
             alert($output['fail']);        
        }

        note('at: ' . $this->getPath());
    }

    
    /**
     * Retrieving the directory path for component storage locations.
     *
     * @return string
     */
    public function getDirectory() :string 
    {
        $path = $this->getPath();
        $pathToArray = explode(DIRECTORY_SEPARATOR,$path);
        array_pop($pathToArray);
        $directory = implode(DIRECTORY_SEPARATOR,$pathToArray);

        return $directory;
    }


    /**
     * It is specified that the component is to be created in the Workbench environment.
     *
     * @return bool
     */
    public function createdComponentOnWorkbench() :bool
    {
        return $this->option('workbench');
    }

 
    /**
     * The main subdirectory determines the storage location for the component.
     *
     * @return string
     */
    abstract protected function subDirectory(); 

    /**
    * The Blade template returns a component.
    */
    abstract protected function template();

        
    /**
     * This method returns an array where the `success` key holds a value indicating the process succeeded, 
     * and the `fail` key indicates it failed.
     *
     * @return array
     */
    abstract protected function outputMessages();
}
