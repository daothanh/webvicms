<?php

namespace Modules\Slider\Events;

use Modules\Media\Repositories\DeletingMedia;
use Modules\Slider\Entities\SliderItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SliderItemWasDeleting implements DeletingMedia
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $entity;

    /**
     * Create a new event instance.
     *
     * @param SliderItem $sliderItem
     */
    public function __construct(SliderItem $sliderItem)
    {
        $this->entity = $sliderItem;
    }

    /**
     * Get the entity ID
     * @return int
     */
    public function getEntityId()
    {
        // dd($this->entity->title);
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
