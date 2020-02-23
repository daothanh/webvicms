<?php

namespace Modules\Media\Events;

use Modules\Core\Repositories\EntityIsChanging;
use Modules\Media\Entities\Media;
use Modules\Core\Events\AbstractEntityHook;

final class FileIsUpdating extends AbstractEntityHook implements EntityIsChanging
{
    /**
     * @var Media
     */
    private $file;

    public function __construct(Media $file, array $attributes)
    {
        $this->file = $file;
        parent::__construct($attributes);
    }

    /**
     * @return Media
     */
    public function getFile()
    {
        return $this->file;
    }
}
