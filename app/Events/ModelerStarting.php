<?php
namespace App\Events;

use App\Managers\ModelerManager;

/**
 * Represents an event that the modeler is starting.
 * Any listeners can interact with the modeler manager to perform things such as 
 * script inclusion.
 */
final class ModelerStarting
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public ModelerManager $manager)
    {
    }

}
