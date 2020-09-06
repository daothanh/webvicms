<?php

namespace Modules\Testimonial\Events;

use Modules\Media\Repositories\StoringMedia;
use Modules\Testimonial\Entities\Testimonial;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestimonialWasUpdated implements StoringMedia
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $entity;

    protected $data;

    /**
     * Create a new event instance.
     *
     * @param Testimonial $testimonial
     * @param $data
     */
    public function __construct(Testimonial $testimonial, $data)
    {
        $this->entity = $testimonial;
        $this->data = $data;
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
     * Return the entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Return the ALL data sent
     * @return array
     */
    public function getSubmissionData()
    {
        return $this->data;
    }
}
