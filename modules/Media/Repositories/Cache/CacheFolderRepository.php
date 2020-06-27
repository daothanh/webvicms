<?php


namespace Modules\Media\Repositories\Cache;


use Illuminate\Database\Eloquent\Collection;
use Modules\Media\Entities\Media;
use Modules\Media\Repositories\FolderRepository;

class CacheFolderRepository extends \Modules\Core\Repositories\Cache\BaseCacheDecorator implements \Modules\Media\Repositories\FolderRepository
{

    protected $repository;

    public function __construct(FolderRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function findFolder(int $folderId)
    {
        return $this->remember(function () use ($folderId) {
            return $this->repository->findFolder($folderId);
        });
    }

    /**
     * @inheritDoc
     */
    public function allChildrenOf(Media $folder)
    {
        return $this->remember(function () use ($folder) {
            return $this->repository->allChildrenOf($folder);
        });
    }

    public function move(Media $folder, Media $destination): Media
    {
        return $this->remember(function () use ($folder, $destination) {
            return $this->repository->move($folder, $destination);
        });
    }

    /**
     * @inheritDoc
     */
    public function findFolderOrRoot($folderId): Media
    {
        return $this->remember(function () use ($folderId) {
            return $this->repository->findFolderOrRoot($folderId);
        });
    }
}
