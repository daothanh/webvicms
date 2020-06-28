<?php

namespace Modules\Commerce\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Commerce\Entities\Cart;
use Modules\Commerce\Events\CartWasCreated;
use Modules\Commerce\Notifications\BookingToUser;
use Modules\User\Entities\User;

class HandleCart
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event instanceof CartWasCreated) {
            /** @var Cart $cart */
            $cart = $event->getEntity();
            /** @var User $user */
            $user = $cart->user() ?? null;
            if ($user) {
                $user->notify(new BookingToUser($cart, $user));
            } else {
                $buyer = $cart->buyer();
                if ($buyer && $buyer->email) {
                    (new User(['email' => $buyer->email]))->notify(new BookingToUser($cart));
                }
            }
        }
    }
}
