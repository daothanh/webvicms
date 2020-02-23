<?php

namespace Modules\User\Blade;

class AuthorDirective
{
    public $entity;
    public $currentUserId;
    public $fieldName;

    public function show($arguments)
    {
        $this->extractArguments($arguments);

        $fieldName = $this->fieldName;
        $currentUser = null;
        $accounts = [];

        $currentUserId = \Request::get($fieldName);
        if (!$currentUserId) {
            if ($this->entity)
            {
                $currentUserId = $this->entity->{$this->fieldName};
            } elseif (\Auth::check()) {
                $currentUserId = \Auth::user()->id;
            }
        }

        if ($currentUserId) {
            $currentUser = get_user_by_id($currentUserId);
            $accounts[$currentUser->id] = $currentUser->name;
        }

        return view('user::admin.directive.author', compact('currentUser', 'accounts', 'fieldName'));
    }

    private function extractArguments(array $arguments)
    {
        $this->entity = \Arr::get($arguments, 0);
        $this->fieldName = \Arr::get($arguments, 1, 'user_id');
        $this->currentUserId = \Arr::get($arguments, 2, \Auth::user()->id);
    }
}
