<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
  |--------------------------------------------------------------------------
  | Console Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of your Closure based console
  | commands. Each Closure is bound to a command instance allowing a
  | simple approach to interacting with each command's IO methods.
  |
 */
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('notifications:resend {username}', function ($username) {
    $user = app\Models\User::where('username', $username)->firstOrFail();
    $tokens = app\Models\ProcessRequestToken
        ::where('status', 'ACTIVE')
        ->where('user_id', $user->getKey())
        ->get();
    foreach ($tokens as $token) {
        dump($token->id);
        $notification = new app\Notifications\ActivityActivatedNotification($token);
        $user->notify($notification);
    }
})->describe('Resend to user the notifications of his/her active tasks');
