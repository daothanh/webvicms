<?php

namespace Modules\Core\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Core\Contracts\StoringSeo;
use Modules\Core\Entities\Seo;

class HandleEntityChange
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event instanceof StoringSeo) {
            $data = \Arr::get($event->getSubmissionData(), 'seo');
            if (!empty($data)) {
                $entity = $event->getEntity();
                $entityType = $event->getEntityClass();
                foreach ($data as $locale => $seoData) {
                    if ($this->isNotEmpty($seoData)) {
                        $seoData = array_merge($seoData, [
                            'entity_id' => $entity->id,
                            'entity_type' => $entityType,
                            'locale' => $locale
                        ]);
                        $seo = Seo::query()
                            ->where('entity_id', '=', $entity->id)
                            ->where('entity_type', '=', $entityType)
                            ->where('locale', '=', $locale)
                            ->first();
                        if ($seo) {
                            $seo->update($seoData);
                        } else {
                            Seo::create($seoData);
                        }
                    } else {
                        Seo::query()
                            ->where('entity_id', '=', $entity->id)
                            ->where('entity_type', '=', $entityType)
                            ->where('locale', '=', $locale)
                            ->delete();
                    }
                }
            }
        }
    }

    protected function isNotEmpty($data)
    {
        $isEmpty = true;
        foreach ($data as $key => $v) {
            if ($key !== 'id' && !empty($v)) {
                $isEmpty = false;
                break;
            }
        }
        return !$isEmpty;
    }
}
