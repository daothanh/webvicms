<?php

namespace Modules\Core\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Core\Contracts\DeletingSeo;
use Modules\Core\Entities\Seo;

class HandleEntityDeleting
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
        if ($event instanceof DeletingSeo) {
            $entityId = $event->getEntityId();
            $entityClass = $event->getClassName();
            Seo::query()->where('entity_id', '=', $entityId)->where('entity_type', '=', $entityClass)->delete();
        }
    }
}
