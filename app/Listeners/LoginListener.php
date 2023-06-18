<?php
namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Login;

final class LoginListener
{
    /**
     * Handle the event.
     *
     * @param Login $user
     */
    public function handle(Login $event): void
    {
        // Grab our user that was logged in
        $user = $event->user;
        // Update the last_login
        $user->loggedin_at = Carbon::now();
        $user->save();
    }
}
