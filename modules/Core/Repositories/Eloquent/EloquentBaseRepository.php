<?php
namespace Modules\Core\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Core\Repositories\BaseRepository as BaseRepositoryInterface;

abstract class EloquentBaseRepository implements BaseRepositoryInterface
{

    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($model, $data)
    {
        $model->update($data);
        return $model;
    }

    public function destroy($model)
    {
        return $model->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQueryBuilder() : Builder
    {
        return $this->model->query();
    }

    /**
     * @inheritdoc
     */
    public function findByAttributes(array $attributes)
    {
        $query = $this->buildQueryByAttributes($attributes);

        return $query->first();
    }

    /**
     * Get all the records of the resource
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Collection|Illuminate\Database\Eloquent\Builder[]
     */
    public function all($columns = ['*'])
    {
        return $this->newQueryBuilder()->get($columns);
    }


    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }
        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where('id', 'LIKE', "%{$keyword}%");
        }

        $params = $request->all();
        $attributes = $this->model->getAttributes();
        foreach ($params as $param => $val) {
            if (in_array($param, $attributes)) {
                $query->where($param, '=', $val);
            }
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $query->orderBy('order', 'asc')->orderBy($request->get('order_by'), $order);
        } else {
            $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
        }

        if ($request->get('group_by') !== null) {
            $query->groupBy(explode(",", $request->get('group_by')));
        }
        return $query->paginate($request->get('per_page', 10));
    }

    /**
     * Build Query to catch resources by an array of attributes and params
     * @param  array $attributes
     * @param  null|string $orderBy
     * @param  string $sortOrder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildQueryByAttributes(array $attributes, $orderBy = null, $sortOrder = 'asc')
    {
        $query = $this->newQueryBuilder();

        foreach ($attributes as $field => $value) {
            $query = $query->where($field, $value);
        }

        if (null !== $orderBy) {
            $query->orderBy($orderBy, $sortOrder);
        }

        return $query;
    }
}
