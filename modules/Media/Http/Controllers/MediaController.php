<?php

namespace Modules\Media\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Http\Controllers\Controller;
use Modules\Media\Events\FileWasUploaded;
use Modules\Media\Http\Requests\UploadDropzoneMediaRequest;
use Modules\Media\Http\Requests\UploadMediaRequest;
use Modules\Media\Image\ThumbnailManager;
use Modules\Media\Repositories\MediaRepository;
use Modules\Media\Services\FileService;
use Modules\Media\Transformers\MediaTransformer;

class MediaController extends Controller
{
    /**
     * @var FileService
     */
    private $fileService;
    public function __construct(MediaRepository $file, ThumbnailManager $thumbnailsManager, FileService $fileService)
    {
        $this->file = $file;
        $this->fileService = $fileService;
        $this->thumbnailsManager = $thumbnailsManager;
    }

    public function ckIndex(Request $request)
    {
        $user = $request->user();
        $request->merge(['user_id' => $user->id]);
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
        return $this->view('media::.grid.ckeditor');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function grid(Request $request)
    {
        /*$files = $this->file->allForGrid();
        $thumbnails = $this->thumbnailsManager->all();*/
        $user = $request->user();
        $request->merge(['user_id' => $user->id]);
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

        return $this->view('media::grid.general');
    }

    public function store(UploadMediaRequest $request) : JsonResponse
    {
        $savedFile = $this->fileService->store($request->file('file'), $request->get('parent_id'));

        if (is_string($savedFile)) {
            return response()->json([
                'error' => $savedFile,
            ], 409);
        }

        event(new FileWasUploaded($savedFile));

        return response()->json($savedFile->toArray());
    }

    public function storeDropzone(UploadDropzoneMediaRequest $request) : JsonResponse
    {
        $savedFile = $this->fileService->store($request->file('file'));

        if (is_string($savedFile)) {
            return response()->json([
                'error' => $savedFile,
            ], 409);
        }

        event(new FileWasUploaded($savedFile));

        return response()->json($savedFile->toArray());
    }

    protected function convertDataTableParams(Request $request)
    {
        $cols = $request->get('columns');
        $data = $request->except(['columns']);
        if (!empty($cols)) {
            $start = $request->get('start', 0);
            $length = $request->get('length', 25);
            $page = ($start / $length) + 1;

            $requestData = [
                'per_page' => $length,
                'page' => $page,
                'draw' => $request->get('draw'),
                'order_by' => $cols[$request->get('order')[0]['column']]['data'],
                'order' => $request->get('order')[0]['dir'] === 'asc' ? 'ascending' : 'descending',
                'search' => $request->get('search')['value']
            ];
            foreach ($cols as $col) {
                if ($col['searchable'] && isset($col['search']['value'])) {
                    $requestData[$col['data']] = $col['search']['value'];
                }
            }
            $data = array_merge($data, $requestData);
            $request->replace($data);
        }
        return $request;
    }
}
