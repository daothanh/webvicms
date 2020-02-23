<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiRoleTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $role = [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'urls' => [
                'edit' => $this->getEditUrl(),
                'delete' => $this->getDeleteUrl()
            ]
        ];
        foreach (locales() as $locale) {
            $role[$locale] = [];
            $translatedTerm = $this->translateOrNew($locale);
            foreach ($this->translatedAttributes as $translatedAttribute) {
                $role[$locale][$translatedAttribute] = $translatedTerm->$translatedAttribute;
            }
        }
        return $role;
    }
}
