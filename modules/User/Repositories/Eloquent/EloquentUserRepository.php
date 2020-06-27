<?php
namespace Modules\User\Repositories\Eloquent;

use Modules\User\Repositories\UserRepository as UserInterface;
use \Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\User\Events\UserWasCreated;
use Modules\User\Events\UserWasDeleting;
use Modules\User\Events\UserWasUpdated;
use Modules\User\Entities\User;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class EloquentUserRepository extends EloquentBaseRepository implements UserInterface
{

    public function create($data)
    {
        $user = $this->model->create($data);
        event(new UserWasCreated($user, $data));
        return $user;
    }

    public function update($model, $data)
    {
        $model->update($data);
        event(new UserWasUpdated($model, $data));
        return $model;
    }

    public function destroy($model)
    {
        event(new UserWasDeleting($model));
        return $model->delete();
    }

    public function generateTokenFor(User $user)
    {
        return app(\Modules\User\Repositories\UserTokenRepository::class)->create([
            'user_id' => $user->id,
            'access_token' => Uuid::uuid4()->toString()
        ]);
    }

    /**
     *
     * @param Request $request
     * @param null $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('email', 'LIKE', "%{$keyword}%")
                    ->orWhere('id', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->get('name') !== null) {
            $name = $request->get('name');
            $query->where('name', 'LIKE', "%{$name}%");
        }

        if ($request->get('email') !== null) {
            $email = $request->get('email');
            $query->where('email', 'LIKE', "%{$email}");
        }

        if ($request->get('role_name') !== null) {
            $roleName = $request->get('role_name');
            $query->whereHas('roles', function ($q) use ($roleName) {
                $q->where('name', '=', $roleName);
            });
        }

        if ($request->get('role_id') !== null) {
            $roleId = $request->get('role_id');
            $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('id', '=', $roleId);
            });
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if ($request->get('group_by') !== null) {
            $query->groupBy(explode(",", $request->get('group_by')));
        }
        return $query->paginate($request->get('per_page', 10));
    }
}
