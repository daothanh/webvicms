<?php

namespace Modules\Media\Events;

use Modules\Core\Repositories\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

class FolderIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
