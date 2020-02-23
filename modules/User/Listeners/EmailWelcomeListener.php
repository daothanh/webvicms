<?php

namespace Modules\User\Listeners;

use Mail;
use Illuminate\Auth\Events\Verified;
use Modules\User\Emails\WelcomeEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailWelcomeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Verified  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        if ($event->user) {
            $event->user->sendEmailWelcome();
        }
    }
}
