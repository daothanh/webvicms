<?php

namespace Modules\Page\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Contracts\DeletingSeo;
use Modules\Media\Repositories\DeletingMedia;
use Modules\Page\Entities\Page;

class PageWasDeleting implements DeletingMedia, DeletingSeo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $entity;

    /**
     * Create a new event instance.
     *
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->entity = $page;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * Get the entity ID
     * @return int
     */
    public function getEntityId()
    {
        $this->entity->id;
    }

    /**
     * Get the class name the imageables
     * @return string
     */
    public function getClassName()
    {
        return get_class($this->entity);
    }
}
