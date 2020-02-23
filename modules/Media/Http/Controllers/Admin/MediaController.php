<?php

namespace Modules\Media\Http\Controllers\Admin;

use Modules\Media\Http\Requests\UpdateMediaRequest;
use Modules\Media\Image\Imagy;
use Modules\Media\Image\ThumbnailManager;
use Modules\Media\Entities\Media;
use Modules\Media\Repositories\MediaRepository;
use Illuminate\Contracts\Config\Repository;
use Modules\Core\Http\Controllers\BaseAdminController;

class MediaController extends BaseAdminController
{
    /**
     * @var MediaRepository
     */
    private $file;
    /**
     * @var Repository
     */
    private $config;
    /**
     * @var Imagy
     */
    private $imagy;
    /**
     * @var ThumbnailManager
     */
    private $thumbnailsManager;

    public function __construct(MediaRepository $file, Repository $config, Imagy $imagy, ThumbnailManager $thumbnailsManager)
    {
        parent::__construct();
        $this->file = $file;
        $this->config = $config;
        $this->imagy = $imagy;
        $this->thumbnailsManager = $thumbnailsManager;
    }

    public function index()
    {
        $this->seo()->setTitle(trans('Media'));
        $this->breadcrumb->addItem(trans('Media'));
        return $this->view('media::admin.media.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Media $media
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Media $media)
    {
        $allThumbnails = $this->thumbnailsManager->all();
        $thumbnails = [];
        if ($media->isImage()) {
            foreach ($allThumbnails as $thumbnail) {
                $thumbnailName = $thumbnail->name();

                $thumbnails[] = [
                    'name' => $thumbnailName,
                    'path' => $this->imagy->getThumbnail($media->path, $thumbnailName),
                    'size' => $thumbnail->size(),
                ];
            }
        }
        $this->seo()->setTitle(__('media::media.title.edit media'));
        $this->breadcrumb->addItem(__('Media'), route('admin.media.index'));
        $this->breadcrumb->addItem(__('media::media.title.edit media'));
        return $this->view('media::admin.media.edit', compact('media', 'thumbnails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMediaRequest $request
     * @return
     */
    public function update(UpdateMediaRequest $request)
    {
        $media = $this->file->find($request->get('id'));
        $this->file->update($media, $request->all());

        return redirect()->route('admin.media.index')
            ->withSuccess(trans('media::media.messages.file updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Media $file
     * @return
     */
    public function destroy(Media $file)
    {
        $this->imagy->deleteAllFor($file);
        $this->file->destroy($file);

        return redirect()->route('admin.media.index')
            ->withSuccess(trans('media::media.messages.file deleted'));
    }
}
