<?php

namespace Modules\Commerce\Events;

use Modules\Core\Contracts\StoringSeo;
use Modules\Media\Repositories\StoringMedia;
use Modules\Commerce\Entities\Product;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ProductWasUpdated implements StoringMedia, StoringSeo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $entity;

    protected $data;

    /**
     * Create a new event instance.
     *
     * @param Product $product
     * @param $data
     */
    public function __construct(Product $product, $data)
    {
        $this->entity = $product;
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
