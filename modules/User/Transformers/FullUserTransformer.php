<?php

namespace Modules\User\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FullUserTransformer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar(),
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'roles' => !empty($this->roles) && $this->roles->isNotEmpty() ? RoleTransformer::collection($this->roles)->toArray($request) : [],
            'tokens' => !empty($this->tokens) && $this->tokens->isNotEmpty() ? TokenTransformer::collection($this->tokens)->toArray($request) : [],
        ];
    }
}
