<?php

namespace Modules\User\Listeners;

use Illuminate\Support\Arr;
use Modules\User\Events\UserWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeUserToken
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
     * @param  UserWasCreated  $event
     * @return void
     */
    public function handle(UserWasCreated $event)
    {
        $user = $event->getEntity();
        $data = $event->getSubmissionData();

        app(\Modules\User\Repositories\UserRepository::class)->generateTokenFor($user);
        $role = Arr::get($data, 'role', 'user');
        $user->assignRole($role);
    }
}
