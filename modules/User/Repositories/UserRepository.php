<?php
namespace Modules\User\Repositories;

use Modules\User\Entities\User;
use Modules\Core\Repositories\BaseRepository;
use Modules\User\Entities\UserToken;

interface UserRepository extends BaseRepository
{
    /**
     * Make an token for an user
     *
     * @param  User   $user
     * @return UserToken       Token of the user
     */
    public function generateTokenFor(User $user);
}
