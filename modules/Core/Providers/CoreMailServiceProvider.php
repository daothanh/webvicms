<?php

namespace Modules\Core\Providers;

use Illuminate\Support\Arr;
use Modules\Core\Mail\TransportManager\MailTransportManager;
use Illuminate\Mail\MailServiceProvider;

class CoreMailServiceProvider extends MailServiceProvider
{
	public function __construct($app)
    {
        parent::__construct($app);

        $mailSettings = settings('mailservers', 'mail');
        if (!empty($mailSettings)) {
	        $config = array_merge($this->app->make('config')->get('mail'),[
	            'from' => [
	                'address' => Arr::get($mailSettings, 'mail.from.email', env('MAIL_FROM_ADDRESS', 'lienhe@webvi.vn')),
	                'name' => Arr::get($mailSettings, 'mail.from.name', env('MAIL_FROM_NAME', 'Webvi Việt Nam')),
	            ],
                'markdown' => [
                    'theme' => 'default',

                    'paths' => [
                        base_path('modules/Core/Resources/views/mail'),
                    ],
                ]
	        ]);

	        $this->app->make('config')->set('mail', $config);
	    }
    }

    protected function registerSwiftTransport()
    {
    	$this->app->singleton('swift.transport', function () {
            return new MailTransportManager($this->app);
        });
    }
}
