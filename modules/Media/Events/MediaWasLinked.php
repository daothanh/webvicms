<?php

namespace Modules\Media\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Media\Entities\Media;

class MediaWasLinked
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Media $file, $entity)
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
