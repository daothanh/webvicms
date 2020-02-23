<?php

namespace Modules\Media\Services;

use Modules\Media\Entities\Media;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Media\Jobs\CreateThumbnails;
use Modules\Media\Repositories\MediaRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    use DispatchesJobs;

    /**
     * @var MediaRepository
     */
    private $file;
    /**
     * @var Factory
     */
    private $filesystem;

    public function __construct(MediaRepository $file, Factory $filesystem)
    {
        $this->file = $file;
        $this->filesystem = $filesystem;
    }

    /**
     * @param UploadedFile $file
     * @param int $parentId
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileExistsException
     */
    public function store(UploadedFile $file, int $parentId = 0)
    {
        $savedFile = $this->file->createFromFile($file, $parentId);

        $path = $this->getDestinationPath($savedFile->getOriginal('path'));
        $stream = fopen($file->getRealPath(), 'r+');
        $this->filesystem->disk($this->getConfiguredFilesystem())->writeStream($path, $stream, [
            'visibility' => 'public',
            'mimetype' => $savedFile->mimetype,
        ]);

        $this->createThumbnails($savedFile);

        return $savedFile;
    }

    /**
     * Create the necessary thumbnails for the given file
     * @param $savedFile
     */
    private function createThumbnails(Media $savedFile)
    {
        $this->dispatch(new CreateThumbnails($savedFile->path));
    }

    /**
     * @param string $path
     * @return string
     */
    private function getDestinationPath($path)
    {
        if ($this->getConfiguredFilesystem() === 'local') {
            return 'public' . $path;
        }

        return $path;
    }

    /**
     * @return string
     */
    private function getConfiguredFilesystem()
    {
        return config('filesystems.default');
    }
}
