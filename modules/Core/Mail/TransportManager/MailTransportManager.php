<?php

namespace Modules\Core\Mail\TransportManager;

use Illuminate\Mail\TransportManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;

class MailTransportManager extends TransportManager
{
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $mailSettings = settings('mailservers', 'mail');
        if (!empty($mailSettings)) {
            $driver = Arr::get($mailSettings, 'mail.driver', env('MAIL_DRIVER', 'sendmail'));
            $this->setDefaultDriver($driver);
            
            if ($driver == 'smtp' && isset($mailSettings['smtp']) && is_array($mailSettings['smtp'])) {
                $config = array_merge(config('mail'), $mailSettings['smtp']);
                config()->set('mail', $config);
            }

            if ($driver == 'mailgun') {
                $mailgun = array_merge(config('services.mailgun'), $mailSettings['mailgun']);
                config()->set('services.mailgun', $mailgun);
            }
            
        }
    }
}
