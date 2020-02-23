<?php

namespace Modules\User\Auth;

class Authentication implements \Modules\User\Repositories\Authentication
{

    protected $guard;

    public function __construct($guard = null)
    {
        $this->guard = \Auth::guard($guard);
    }

    /**
     * Determines if the current user has access to given permission
     * @param $permission
     * @return bool
     */
    public function hasAccess($permission)
    {
        $user = $this->user();
        if ($user) {
            return $user->can($permission);
        }
        return false;
    }

    /**
     * Determines if the current user has a role
     *
     * @param $roleName
     * @return bool
     */
    public function hasRole($roleName)
    {
        $user = $this->user();
        if ($user) {
            return $user->hasRole($roleName);
        }
        return false;
    }

    /**
     * Check if the user is logged in
     * @return bool
     */
    public function check()
    {
        return $this->guard->check();
    }

    /**
     * Get the currently logged in user
     * @return \Modules\User\Entities\User
     */
    public function user()
    {
        if ($this->check()) {
            return $this->guard->user();
        }
        return null;
    }

    /**
     * Get the ID for the currently authenticated user
     * @return int
     */
    public function id()
    {
        if ($user = $this->user()) {
            $user->id;
        }
        return null;
    }
}
