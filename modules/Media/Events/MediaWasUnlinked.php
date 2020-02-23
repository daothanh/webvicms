<?php

namespace Modules\Media\Events;

use Illuminate\Queue\SerializesModels;

class MediaWasUnlinked
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($imageableId)
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
