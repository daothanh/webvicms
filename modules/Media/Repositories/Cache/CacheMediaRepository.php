<?php


namespace Modules\Media\Repositories\Cache;


use Illuminate\Database\Eloquent\Collection;
use Modules\Media\Entities\Media;
use Modules\Media\Repositories\MediaRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CacheMediaRepository extends \Modules\Core\Repositories\Cache\BaseCacheDecorator implements \Modules\Media\Repositories\MediaRepository
{
    protected $repository;

    public function __construct(MediaRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function createFromFile(UploadedFile $file, int $parentId = 0)
    {
        return $this->repository->createFromFile($file, $parentId);
    }

    /**
     * @inheritDoc
     */
    public function findFileByZoneForEntity($zone, $entity)
    {
        return $this->remember(function () use ($zone, $entity) {
            return $this->repository->findFileByZoneForEntity($zone, $entity);
        });
    }

    /**
     * @inheritDoc
     */
    public function findMultipleFilesByZoneForEntity($zone, $entity)
    {
        return $this->remember(function () use ($zone, $entity) {
            return $this->repository->findMultipleFilesByZoneForEntity($zone, $entity);
        });
    }

    /**
     * @inheritDoc
     */
    public function allChildrenOf(int $folderId): Collection
    {
        return $this->remember(function () use ($folderId) {
            return $this->repository->allChildrenOf($folderId);
        });
    }

    public function findForVirtualPath(string $path)
    {
        return $this->remember(function () use ($path) {
            return $this->repository->findForVirtualPath($path);
        });
    }

    public function allForGrid(): Collection
    {
        return $this->remember(function () {
            return $this->repository->allForGrid();
        });
    }

    public function move(Media $file, Media $destination): Media
    {
        return $this->repository->move($file, $destination);
    }
}
