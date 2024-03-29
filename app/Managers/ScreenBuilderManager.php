<?php
namespace App\Managers;

final class ScreenBuilderManager
{
    private array $javascriptRegistry;

    /**
     * Start our screen builder manager, creating an empty javascript registry
     */
    public function __construct()
    {
        $this->javascriptRegistry = [];
    }

    /**
     * Add a new script to the modeler load.  These scripts can then interact with the modeler 
     * during it's startup lifecycle to do this such as register new node types.
     *
     * @param string $script Path to the javascript to load
     */
    public function addScript($script): void
    {
        $this->javascriptRegistry[] = $script;
    }

    /**
     * Retrieve the list of scripts that have been added. This is used in the modeler blade 
     * to execute each script in a script tag before the modeler is started.
     * 
     * @return array Collection of paths to scripts to load
     */
    public function getScripts(): array
    {
        return $this->javascriptRegistry;
    }
}