<?php
namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

class LoginListener
{
    /**
     * Handle the event.
     *
     * @param Login $user
     * @return void
     */
    public function handle(Login $event)
    {
        // Grab our user that was logged in
        $user = $event->user;
        // Update the last_login
        $user->loggedin_at = Carbon::now();
        $user->save();
    }
}
