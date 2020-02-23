<?php

namespace Modules\User\Events;

use Modules\Media\Repositories\DeletingMedia;
use Modules\User\Entities\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserWasDeleting implements DeletingMedia
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $entity;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->entity = $user;
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

    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @inheritDoc
     */
    public function getEntityId()
    {
        $this->entity->id;
    }

    /**
     * @inheritDoc
     */
    public function getClassName()
    {
        return get_class($this->entity);
    }
}
