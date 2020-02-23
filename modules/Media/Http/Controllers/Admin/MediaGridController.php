<?php

namespace Modules\Media\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Media\Image\ThumbnailManager;
use Modules\Media\Repositories\MediaRepository;
use Modules\Media\Transformers\MediaTransformer;
use Modules\Core\Http\Controllers\BaseAdminController;

class MediaGridController extends BaseAdminController
{
    /**
     * @var MediaRepository
     */
    private $file;
    /**
     * @var ThumbnailManager
     */
    private $thumbnailsManager;

    public function __construct(MediaRepository $file, ThumbnailManager $thumbnailsManager)
    {
        parent::__construct();

        $this->file = $file;
        $this->thumbnailsManager = $thumbnailsManager;
    }

    /**
     * A grid view for the upload button
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        /*$files = $this->file->allForGrid();
        $thumbnails = $this->thumbnailsManager->all();*/
        if ($request->isXmlHttpRequest()) {
            $files = $this->file->serverPagingFor($this->convertDataTableParams($request));
            $output = [
                "draw" => $request->get('draw'),
                "recordsTotal" => $files->total(),
                "recordsFiltered" => $files->total(),
                'data' => MediaTransformer::collection($files)
            ];
            return response()->json($output);
        }

        return $this->view('media::admin.media.grid.general');
    }

    /**
     * A grid view of uploaded files used for the wysiwyg editor
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function ckIndex(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $files = $this->file->serverPagingFor($this->convertDataTableParams($request));
            $output = [
                "draw" => $request->get('draw'),
                "recordsTotal" => $files->total(),
                "recordsFiltered" => $files->total(),
                'data' => MediaTransformer::collection($files)
            ];
            return response()->json($output);

        }
        return $this->view('media::admin.media.grid.ckeditor');
    }
}
