<?php
namespace App\Events;

use App\Managers\ScreenBuilderManager;

/**
 * Represents an event that the screen builder is starting.
 * Any listeners can interact with the builder manager to perform things such as 
 * script inclusion.
 */
final class ScreenBuilderStarting
{
    /**
     * Create a new event instance.
     * @param string $type The type of screen that is launching
     * @return void
     */
    public function __construct(public ScreenBuilderManager $manager, public $type)
    {
    }

}
