<?php

namespace Modules\User\Events;

use Illuminate\Broadcasting\Channel;
use Modules\Media\Repositories\StoringMedia;
use Modules\User\Entities\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserWasUpdated implements StoringMedia
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $entity;
    protected $data;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param array $data
     */
    public function __construct(User $user, array $data)
    {
        $this->entity = $user;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
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
    public function getSubmissionData()
    {
        return $this->data;
    }
}
