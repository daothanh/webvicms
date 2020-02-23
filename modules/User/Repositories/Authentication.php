<?php

namespace Modules\User\Repositories;

interface Authentication
{
    /**
     * Determines if the current user has access to given permission
     * @param $permission
     * @return bool
     */
    public function hasAccess($permission);

    /**
     * Determines if the current user has a role
     *
     * @param $roleName
     * @return bool
     */
    public function hasRole($roleName);

    /**
     * Check if the user is logged in
     * @return bool
     */
    public function check();

    /**
     * Get the currently logged in user
     * @return \Modules\User\Entities\User
     */
    public function user();

    /**
     * Get the ID for the currently authenticated user
     * @return int
     */
    public function id();
}
