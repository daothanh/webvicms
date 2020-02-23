<?php

namespace Modules\Tag\Events;

use Modules\Core\Repositories\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

class TagIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
