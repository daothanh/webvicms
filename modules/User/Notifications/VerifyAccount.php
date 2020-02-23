<?php

namespace Modules\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;

class VerifyAccount extends VerifyEmail
{
    protected function verificationUrl($notifiable)
    {
        $token = $notifiable->getFirstToken();
        if (!$token) {
            $token = app(\Modules\User\Repositories\UserRepository::class)->generateTokenFor($notifiable);
        }
        return \URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(\Config::get('auth.verification.expire', 60)),
            ['id' => $notifiable->getKey(), 'access_token' => $token->access_token]
        );
    }
}
