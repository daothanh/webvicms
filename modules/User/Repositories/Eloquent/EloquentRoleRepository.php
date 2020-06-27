<?php

namespace Modules\User\Repositories\Eloquent;

use \Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\User\Repositories\RoleRepository as RoleInterface;
use Illuminate\Http\Request;

class EloquentRoleRepository extends EloquentBaseRepository implements RoleInterface
{
    public function findRoleByName($name)
    {
        return $this->newQueryBuilder()->where('name', '=', $name)->first();
    }
    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('translations', function ($q) use ($keyword) {
                    $q->where('title', 'LIKE', "%{$keyword}%");
                })
                    ->orWhere('guard_name', 'LIKE', "%{$keyword}%")
                    ->orWhere('id', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->get('guard_name') !== null) {
            $query->where('guard_name', '=', $request->get('guard_name'));
        }

        if ($request->get('name') !== null) {
            $query->where('name', '=', $request->get('name'));
        }

        return $query->paginate($request->get('per_page', 10));
    }
}
