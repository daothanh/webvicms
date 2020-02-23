<?php

namespace Modules\Media\Http\Controllers\Api;

use Modules\Media\Entities\Media;
use Modules\Media\Events\MediaWasLinked;
use Modules\Media\Events\MediaWasUnlinked;
use Modules\Media\Image\Imagy;
use Modules\Media\Repositories\FolderRepository;
use Modules\Media\Services\FileService;
use Modules\Media\Events\FileWasUploaded;
use Modules\Media\Image\Helpers\FileHelper;
use Modules\Media\Repositories\MediaRepository;
use Modules\Core\Http\Controllers\ApiController;
use Modules\Media\Http\Requests\UploadMediaRequest;
use Modules\Media\Transformers\MediaTransformer;
use Modules\Media\Http\Requests\UploadDropzoneMediaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MediaController extends ApiController
{
    /**
     * @var FileService
     */
    private $fileService;

    /**
     * @var MediaRepository
     */
    private $file;

    /**
     * @var FolderRepository
     */
    private $folder;

    /**
     * @var Imagy
     */
    private $imagy;

    public function __construct(FileService $fileService, MediaRepository $file, FolderRepository $folder, Imagy $imagy)
    {
        $this->fileService = $fileService;
        $this->file = $file;
        $this->folder = $folder;
        $this->imagy = $imagy;
    }

    public function index(Request $request)
    {
        if ($request->get('columns') !== null) {
            // For datatables.net
            $medias = $this->file->serverPagingFor($this->convertDataTableParams($request));
            $output = [
                "draw" => $request->get('draw'),
                "recordsTotal" => $medias->total(),
                "recordsFiltered" => $medias->total(),
                'data' => MediaTransformer::collection($medias),
            ];
            return response()->json($output);
        }
        return MediaTransformer::collection($this->file->serverPagingFor($this->convertDataTableParams($request)));
    }

    public function find(Media $file)
    {
        return new MediaTransformer($file);
    }

    public function findFirstByZoneEntity(Request $request)
    {
        $imageable = DB::table('mediables')
            ->where('fileable_id', $request->get('entity_id'))
            ->where('zone', '=', $request->get('zone'))
            ->where('fileable_type', '=', $request->get('entity'))
            ->first();

        if ($imageable === null) {
            return response()->json(null);
        }

        $file = $this->file->find($imageable->file_id);

        if ($file === null) {
            return response()->json(['data' => null]);
        }

        return new MediaTransformer($file);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UploadMediaRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UploadMediaRequest $request): JsonResponse
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

    public function storeDropzone(UploadDropzoneMediaRequest $request): JsonResponse
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

    public function update(Media $file, Request $request)
    {
        $data = $request->except(['filename', 'path', 'extension', 'size', 'id', 'thumbnails']);

        $this->file->update($file, $data);

        return response()->json([
            'errors' => false,
            'message' => trans('media::media.messages.file updated'),
        ]);
    }

    /**
     * Link the given entity with a media file
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function linkMedia(Request $request): JsonResponse
    {
        $mediaId = $request->get('mediaId');
        $entityClass = $request->get('entityClass');
        $entityId = $request->get('entityId');
        $order = $request->get('order');

        $entity = $entityClass::find($entityId);
        $zone = $request->get('zone');
        $entity->files()->attach($mediaId, [
            'fileable_type' => $entityClass,
            'zone' => $zone,
            'order' => $order,
        ]);
        $imageable = DB::table('mediables')->whereFileId($mediaId)
            ->whereZone($zone)
            ->where('fileable_type', '=', $entityClass)
            ->first();
        $file = $this->file->find($imageable->file_id);

        $mediaType = FileHelper::getTypeByMimetype($file->mimetype);

        $thumbnailPath = $this->getThumbnailPathFor($mediaType, $file);

        event(new MediaWasLinked($file, $entity));

        return response()->json([
            'error' => false,
            'message' => 'The link has been added.',
            'result' => [
                'path' => $thumbnailPath,
                'imageableId' => $imageable->id,
                'mediaType' => $mediaType,
                'mimetype' => $file->mimetype,
            ],
        ]);
    }

    /**
     * Remove the record in the mediables table for the given id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlinkMedia(Request $request): JsonResponse
    {
        $imageableId = $request->get('imageableId');
        $deleted = DB::table('mediables')->whereId($imageableId)->delete();
        if (!$deleted) {
            return response()->json([
                'error' => true,
                'message' => 'The file was not found.',
            ]);
        }

        event(new MediaWasUnlinked($imageableId));

        return response()->json([
            'error' => false,
            'message' => 'The link has been removed.',
        ]);
    }

    /**
     * Sort the record in the mediables table for the given array
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sortMedia(Request $request): JsonResponse
    {
        $imageableIdArray = $request->get('sortable');

        $order = 1;

        foreach ($imageableIdArray as $id) {
            DB::table('mediables')->whereId($id)->update(['order' => $order]);
            $order++;
        }

        return response()->json(['error' => false, 'message' => 'The items have been reorder.']);
    }

    public function destroy(Media $file)
    {
        $this->deleteMedia($file);

        return response()->json([
            'errors' => false,
            'message' => trans('media::media.messages.file deleted'),
        ]);
    }

    /**
     * Xóa nhiều file
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroyMultiple(Request $request)
    {
        $ids = $request->get('ids');
        $medias = $this->file->newQueryBuilder()
            ->whereIn('id', $ids)
            ->get();
        foreach ($medias as $file) {
            $this->deleteMedia($file);
        }

        return response()->json([
            'errors' => false,
            'message' => trans('media::media::messages.selected items deleted'),
        ]);
    }

    /**
     * Get the path for the given file and type
     * @param string $mediaType
     * @param Media $file
     * @return string
     */
    private function getThumbnailPathFor($mediaType, Media $file): string
    {
        if ($mediaType === 'image') {
            return $this->imagy->getThumbnail($file->path, 'm');
        }

        return $file->path->getRelativeUrl();
    }

    /**
     * @param Media $file
     */
    private function deleteMedia($file)
    {
        if ($file->isFolder()) {
            $this->folder->destroy($file);
        } else {
            $this->imagy->deleteAllFor($file);
            $this->file->destroy($file);
        }
    }
}
