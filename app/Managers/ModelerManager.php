<?php
namespace App\Managers;

final class ModelerManager
{
    private array $javascriptRegistry;

    /**
     * Start our modeler manager, registering our initial javascript 
     * which will add our intial node types to the modeler (base bpmn types/shapes)
     */
    public function __construct()
    {
        $this->javascriptRegistry = [];
        // Include our default javascript for our core controls
        $this->addScript(mix('js/processes/modeler/initialLoad.js'));
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
    public function getScripts()
    {
        return $this->javascriptRegistry;
    }
}