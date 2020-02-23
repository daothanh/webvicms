<?php

namespace Modules\Media\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Media\Repositories\StoringMedia;

class HandleMediaStorage
{
    public function handle($event = null, $data = [])
    {
        if ($event instanceof StoringMedia) {
            $this->handleMultiMedia($event);

            $this->handleSingleMedia($event);
        }
    }

    /**
     * Handle the request for the multi media partial
     * @param StoringMedia $event
     */
    private function handleMultiMedia(StoringMedia $event)
    {
        $entity = $event->getEntity();
        $postMedias = \Arr::get($event->getSubmissionData(), 'medias_multi', []);

        foreach ($postMedias as $zone => $attributes) {
            $syncList = [];
            $orders = $this->getOrdersFrom($attributes);
            $maps = Relation::morphMap();
            $className = get_class($entity);
            $mediableType = array_search($className, $maps);
            if (empty($mediableType)) {
                $mediableType = $className;
            }
            foreach (\Arr::get($attributes, 'files', []) as $fileId) {
                $syncList[$fileId] = [];
                $syncList[$fileId]['mediable_type'] = $mediableType;
                $syncList[$fileId]['zone'] = $zone;
                $syncList[$fileId]['order'] = (int) array_search($fileId, $orders);
            }
            $entity->filesByZone($zone)->sync($syncList);
        }
    }

    /**
     * Handle the request to parse single media partials
     * @param StoringMedia $event
     */
    private function handleSingleMedia(StoringMedia $event)
    {
        $entity = $event->getEntity();
        $postMedia = \Arr::get($event->getSubmissionData(), 'medias_single', []);
        $maps = Relation::morphMap();
        $className = get_class($entity);
        $mediableType = array_search($className, $maps);
        if (empty($mediableType)) {
            $mediableType = $className;
        }
        foreach ($postMedia as $zone => $fileId) {
            if (!empty($fileId)) {
                $entity->filesByZone($zone)->sync([$fileId => ['mediable_type' => $mediableType, 'zone' => $zone, 'order' => null]]);
            } else {
                $entity->filesByZone($zone)->sync([]);
            }
        }
    }

    /**
     * Parse the orders input and return an array of file ids, in order
     * @param array $attributes
     * @return array
     */
    private function getOrdersFrom(array $attributes)
    {
        $orderString = \Arr::get($attributes, 'orders');

        if ($orderString === null) {
            return [];
        }

        $orders = explode(',', $orderString);

        return array_filter($orders);
    }
}
