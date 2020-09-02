<?php

namespace Modules\Media\Transformers;

use Modules\Media\Image\Helpers\FileHelper;
use Modules\Media\Image\Imagy;
use Modules\Media\Image\ThumbnailManager;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaTransformer extends JsonResource
{
    /**
     * @var Imagy
     */
    private $imagy;
    /**
     * @var ThumbnailManager
     */
    private $thumbnailManager;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->imagy = app(Imagy::class);
        $this->thumbnailManager = app(ThumbnailManager::class);
    }

    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'filename' => $this->filename,
            'title' => $this->title,
            'path' => $this->getPath(),
            'is_image' => $this->isImage(),
            'is_folder' => $this->isFolder(),
            'media_type' => $this->media_type,
            'extension' => $this->getExtension(),
            'fa_icon' => FileHelper::getFaIcon($this->media_type, $this->getExtension()),
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'folder_id' => $this->folder_id,
            'thumbnail' => $this->imagy->getThumbnail($this->path, 'thumbnail'),
            'urls' => [
                'delete_url' => $this->getDeleteUrl(),
                'edit_url' => $this->getEditUrl(),
            ],
        ];

        foreach ($this->thumbnailManager->all() as $thumbnail) {
            $thumbnailName = $thumbnail->name();

            $data['thumbnails'][] = [
                'name' => $thumbnailName,
                'path' => $this->imagy->getThumbnail($this->path, $thumbnailName),
                'size' => $thumbnail->size(),
            ];
        }

        return $data;
    }

    private function getPath()
    {
        if ($this->is_folder) {
            return $this->path->getRelativeUrl();
        }

        return (string) $this->path;
    }

    private function getDeleteUrl()
    {
        if (!$this->is_folder) {
            return route('api.media.destroy', $this->id);
        }

        return route('api.media.folders.destroy', $this->id);
    }

    private function getEditUrl()
    {
        return route('admin.media.edit', $this->id);
    }
}
