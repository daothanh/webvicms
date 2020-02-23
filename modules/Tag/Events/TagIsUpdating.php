<?php

namespace Modules\Tag\Events;

use Modules\Core\Repositories\EntityIsChanging;
use Modules\Tag\Entities\Tag;
use Modules\Core\Events\AbstractEntityHook;

class TagIsUpdating extends AbstractEntityHook implements EntityIsChanging
{
    /**
     * @var Tag
     */
    private $tag;

    public function __construct(Tag $tag, $attributes)
    {
        $this->tag = $tag;
        parent::__construct($attributes);
    }

    /**
     * @return Tag
     */
    public function getTag()
    {
        return $this->tag;
    }
}
