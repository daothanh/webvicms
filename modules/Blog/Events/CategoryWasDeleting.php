<?php

namespace Modules\Blog\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Contracts\DeletingSeo;
use Modules\Media\Repositories\DeletingMedia;
use Modules\Blog\Entities\Category;

class CategoryWasDeleting implements DeletingMedia, DeletingSeo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $entity;

    /**
     * Create a new event instance.
     *
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->entity = $category;
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
