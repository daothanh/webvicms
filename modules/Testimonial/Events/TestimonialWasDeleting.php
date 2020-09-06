<?php

namespace Modules\Testimonial\Events;

use Modules\Media\Repositories\DeletingMedia;
use Modules\Testimonial\Entities\Testimonial;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestimonialWasDeleting implements DeletingMedia
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $entity;

    /**
     * Create a new event instance.
     *
     * @param Testimonial $testimonial
     */
    public function __construct(Testimonial $testimonial)
    {
        $this->entity = $testimonial;
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
        return $this->entity->id;
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
