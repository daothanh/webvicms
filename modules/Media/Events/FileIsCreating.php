<?php

namespace Modules\Media\Events;

use Modules\Core\Repositories\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;

final class FileIsCreating extends AbstractEntityHook implements EntityIsChanging
{
}
