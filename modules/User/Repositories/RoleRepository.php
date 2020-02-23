<?php
namespace Modules\User\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface RoleRepository extends BaseRepository{
    public function findRoleByName($name);
}
