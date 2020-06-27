<?php

namespace Modules\Media\Listeners;

use Modules\Media\Events\FolderIsDeleting;
use Modules\Media\Repositories\Eloquent\FolderRepositoryEloquent;

class DeleteAllChildrenOfFolder
{
    /**
     * @var FolderRepositoryEloquent
     */
    private $folder;

    public function __construct(FolderRepositoryEloquent $folder)
    {
        $this->folder = $folder;
    }

    public function handle(FolderIsDeleting $event)
    {
        $children = $this->folder->allChildrenOf($event->folder);
        foreach ($children as $child) {
            $child->delete();
        }
    }
}
