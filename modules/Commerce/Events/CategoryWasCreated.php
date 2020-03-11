<?php

namespace Modules\Commerce\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Contracts\StoringSeo;
use Modules\Media\Repositories\StoringMedia;
use Modules\Commerce\Entities\Category;

class CategoryWasCreated implements StoringMedia, StoringSeo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $entity;

    protected $data;

    /**
     * Create a new event instance.
     *
     * @param Category $category
     * @param $data
     */
    public function __construct(Category $category, $data)
    {
        $this->entity = $category;
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

    public function getEntityClass()
    {
        return get_class($this->entity);
    }
}
