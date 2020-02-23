<?php

namespace Modules\User\Emails;

use Modules\User\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Settings;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $account;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->account = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $namespace = Settings::get('website', 'frontend_theme', 'simple');
        return $this->view($namespace.'::emails.auth.welcome');
    }
}
