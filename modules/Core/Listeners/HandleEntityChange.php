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

                $locales = locales();
                $isMultipleLocale = false;
                foreach ($locales as $locale) {
                    if (isset($data[$locale])) {
                        $isMultipleLocale = true;
                        $seoData = $data[$locale];
                        if ($this->isNotEmpty($seoData)) {
                            $seoData['entity_id'] = $entity->id;
                            $seoData['entity_type'] = $entityType;
                            $seoData['locale'] = $locale;
                            $seo = Seo::query()
                                ->where('entity_id', '=', $entity->id)
                                ->where('entity_type', '=', $entityType)
                                ->where('locale', '=', $locale)
                                ->first();
                            if ($seo) {
                                $seo->update($seoData);
                            } else {
                                $seo = Seo::create($seoData);
                            }
                        }
                    }
                }

                if (!$isMultipleLocale && $this->isNotEmpty($data)) {
                    $data['entity_id'] = $entity->id;
                    $data['entity_type'] = $entityType;
                    $data['locale'] = locale();
                    $seo = Seo::query()
                        ->where('entity_id', '=', $entity->id)
                        ->where('entity_type', '=', $entityType)
                        ->first();
                    if ($seo) {
                        $seo->update($data);
                    } else {
                        $seo = Seo::create($data);
                    }
                }
            }
        }
    }

    protected function isNotEmpty($data)
    {
        $isEmpty = true;
        foreach ($data as $v) {
            if (!empty($v)) {
                $isEmpty = false;
                break;
            }
        }
        return !$isEmpty;
    }
}
