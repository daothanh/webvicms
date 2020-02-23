<?php

namespace Modules\Media\Blade;

use Modules\Media\Repositories\MediaRepository;
use Illuminate\Support\Arr;

class MediaSingleDirective
{
    /**
     * @var string
     */
    private $zone;
    /**
     * @var
     */
    private $entity;
    /**
     * @var string|null
     */
    private $view;
    /**
     * @var string|null
     */
    private $name;

    private $thumbnail = 's';

    public function show($arguments)
    {
        $this->extractArguments($arguments);

        $view = $this->view ?: 'media.fields.new-file-link-single';

        $zone = $this->zone;

        $name = $this->name ?: ucwords(str_replace('_', ' ', $this->zone));

        $media = null;
        if ($this->entity !== null) {
            $media = $this->entity->filesByZone($this->zone)->first();
        } else {
            $mediaId = old('medias_single.'.$zone);
            if ($mediaId) {
                $media = app(MediaRepository::class)->find($mediaId);
            }
        }
        return view('media::admin.'.$view, compact('media', 'zone', 'name'));
    }

    /**
     * Extract the possible arguments as class properties
     * @param array $arguments
     */
    private function extractArguments(array $arguments)
    {
        $this->zone = Arr::get($arguments, 0);
        $this->entity = Arr::get($arguments, 1);
        $this->name = Arr::get($arguments, 2);
        $this->thumbnail = Arr::get($arguments, 3);
        $this->view = Arr::get($arguments, 4);
    }
}
