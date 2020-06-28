<?php
namespace Modules\Media\Repositories\Eloquent;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Media\Repositories\FolderRepository as FolderInterface;
use \Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Media\Events\FolderIsCreating;
use Modules\Media\Events\FolderIsDeleting;
use Modules\Media\Events\FolderIsUpdating;
use Modules\Media\Events\FolderStartedMoving;
use Modules\Media\Events\FolderWasCreated;
use Modules\Media\Entities\Media;
use Modules\Media\Collection\NestedFoldersCollection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Modules\Media\Events\FolderWasUpdated;

class EloquentFolderRepository extends EloquentBaseRepository implements FolderInterface {
    public function all($columns = ['*'])
    {
        return $this->model->where('is_folder', 1)->orderBy('created_at', 'DESC')->get($columns);
    }

    /**
     * Find a folder by its ID
     * @param int $folderId
     * @return Media|null
     */
    public function findFolder(int $folderId)
    {
        return $this->model->where('is_folder', 1)->where('id', $folderId)->first();
    }

    public function create($data)
    {
        $data = [
            'filename' => Arr::get($data, 'name'),
            'path' => $this->getPath($data),
            'is_folder' => true,
            'folder_id' => Arr::get($data, 'parent_id'),
        ];
        event($event = new FolderIsCreating($data));
        $folder = $this->model->create($event->getAttributes());

        event(new FolderWasCreated($folder, $data));

        return $folder;
    }

    public function update($model, $data)
    {
        $previousData = [
            'filename' => $model->filename,
            'path' => $model->path,
        ];
        $formattedData = [
            'filename' => Arr::get($data, 'name'),
            'path' => $this->getPath($data),
            'parent_id' => Arr::get($data, 'parent_id'),
        ];

        event($event = new FolderIsUpdating($formattedData));
        $model->update($event->getAttributes());

        event(new FolderWasUpdated($model, $formattedData, $previousData));

        return $model;
    }

    public function destroy($folder)
    {
        event(new FolderIsDeleting($folder));

        return $folder->delete();
    }

    /**
     * @param Media $folder
     * @return Collection
     */
    public function allChildrenOf(Media $folder)
    {
        $path = $folder->path->getRelativeUrl();

        return $this->model->where('path', 'like', "{$path}%")->get();
    }

    public function allNested(): NestedFoldersCollection
    {
        return new NestedFoldersCollection($this->all());
    }

    public function move(Media $folder, Media $destination): Media
    {
        $previousData = [
            'filename' => $folder->filename,
            'path' => $folder->path,
        ];

        $folder->update([
            'path' => $this->getNewPathFor($folder->filename, $destination),
            'folder_id' => $destination->id,
        ]);

        event(new FolderStartedMoving($folder, $previousData));

        return $folder;
    }

    /**
     * Find the folder by ID or return a root folder
     * which is an instantiated File class
     * @param int $folderId
     * @return Media
     */
    public function findFolderOrRoot($folderId): Media
    {
        $destination = $this->findFolder($folderId);

        if ($destination === null) {
            $destination = $this->makeRootFolder();
        }

        return $destination;
    }

    private function getNewPathFor(string $filename, Media $folder)
    {
        return $this->removeDoubleSlashes($folder->path->getRelativeUrl() . '/' . Str::slug($filename));
    }

    private function removeDoubleSlashes(string $string) : string
    {
        return str_replace('//', '/', $string);
    }

    /**
     * @param array $data
     * @return string
     */
    private function getPath(array $data): string
    {
        if (array_key_exists('parent_id', $data)) {
            $parent = $this->findFolder($data['parent_id']);
            if ($parent !== null) {
                return $parent->path->getRelativeUrl() . '/' . Str::slug(Arr::get($data, 'name'));
            }
        }

        return $this->getMediaPath() . Str::slug(Arr::get($data, 'name'));
    }

    /**
     * Create an instantiated File entity, appointed as root
     * @return Media
     */
    private function makeRootFolder() : Media
    {
        return new Media([
            'id' => 0,
            'folder_id' => 0,
            'path' => $this->getMediaPath(),
        ]);
    }

    public function serverPagingFor(Request $request, $relations = null)
    {
        // TODO: Implement serverPagingFor() method.
    }

    private function getMediaPath() {
        return config('media.files-path');
    }
}
