<?php

namespace Modules\Media\Entities;

use Modules\Media\Image\Facade\Imagy;
use Modules\Media\Image\Helpers\FileHelper;
use Modules\Media\ValueObjects\MediaPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Responsable;

class Media extends Model implements Responsable
{
    protected $table = 'medias';

    protected $fillable = ['user_id', 'filename', 'path', 'mimetype', 'extension', 'filesize', 'is_folder', 'folder_id', 'title', 'description'];
    protected $appends = ['path_string', 'media_type'];
    protected $casts = ['is_folder' => 'boolean',];
    /**
     * All the different images types where thumbnails should be created
     * @var array
     */
    private $imageExtensions = ['jpg', 'png', 'jpeg', 'gif'];

    /**
     * @param $value
     * @return MediaPath
     */
    public function getPathAttribute($value)
    {
        return new MediaPath($value);
    }


    /**
     * @return string
     */
    public function getPathStringAttribute()
    {
        return (string) $this->path;
    }

    /**
     * Đường dẫn của file
     *
     * @param null|string $thumbnail
     * @return false|string
     */
    public function getUrl($thumbnail = null) {
        if ($thumbnail) {
            return $this->getThumbnail($thumbnail);
        }
        return $this->getPathStringAttribute();
    }

    public function getMediaTypeAttribute()
    {
        return FileHelper::getTypeByMimetype($this->mimetype);
    }

    public function parentFolder()
    {
        return $this->belongsTo(__CLASS__, 'folder_id');
    }

    public function isFolder(): bool
    {
        return $this->is_folder;
    }

    public function isImage()
    {
        return in_array($this->getExtension(), $this->imageExtensions);
    }

    public function getExtension()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    public function getThumbnail($type)
    {
        if ($this->isImage() && $this->getKey()) {
            return Imagy::getThumbnail($this->path, $type);
        }

        return false;
    }

    /**
     * Hiển thị ảnh
     *
     * @param false $thumbnail
     * @param array $attributes
     * @return string|null
     */
    public function getImage($thumbnail = false, $attributes = []) {
        if ($this->isImage()) {
            $src = $this->getUrl($thumbnail);
            $htmlAttributes = $this->getHtmlAttributes($attributes);
            return '<img src="'.$src.'" '.implode(" ", $htmlAttributes).'/>';
        }
        return null;
    }

    /**
     * Link của file
     *
     * @param null $label
     * @param array $attributes
     * @return string|null
     */
    public function getLink ($label = null, $attributes = []) {
        if ($this->path) {
            $htmlAttributes = $this->getHtmlAttributes($attributes);
            if ($label === null) {
                $label = $this->title ?? $this->getUrl();
            }
            return '<a href="'.$this->getUrl().'" '.implode(" ", $htmlAttributes).'>'.$label.'</a>';
        }
        return null;
    }

    /**
     * Trả về mảng attribute cho html
     *
     * @param $attributes
     * @return array
     */
    protected function getHtmlAttributes ($attributes) {
        $attributes = array_merge([
            'alt' => $this->title,
            'title' => $this->title,
            'description' => $this->description
        ], $attributes);
        $htmlAttributes = [];
        foreach ($attributes as $attribute => $value) {
            if ($value) {
                $htmlAttributes[$attribute] = $attribute.'="'.$value.'"';
            }
        }
        return $htmlAttributes;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function toResponse($request)
    {
        return response()
            ->file(public_path($this->path->getRelativeUrl()), [
                'Content-Type' => $this->mimetype,
            ]);
    }
}
