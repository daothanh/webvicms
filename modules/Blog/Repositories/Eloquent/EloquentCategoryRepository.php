<?php

namespace Modules\Blog\Repositories\Eloquent;

use Illuminate\Support\Str;
use Modules\Blog\Entities\Category;
use Modules\Blog\Repositories\CategoryRepository;
use \Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\Blog\Events\CategoryWasCreated;
use Modules\Blog\Events\CategoryWasDeleting;
use Modules\Blog\Events\CategoryWasUpdated;
use Illuminate\Http\Request;

class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepository
{

    public function create($data)
    {
        $category = $this->model->create($data);
        event(new CategoryWasCreated($category, $data));
        return $category;
    }

    public function update($category, $data)
    {
        $category->update($data);
        event(new CategoryWasUpdated($category, $data));
        return $category;
    }

    public function destroy($category)
    {
        //event(new CategoryWasDeleting($category));
        return $category->delete();
    }

    public function forceDestroy($category)
    {
        event(new CategoryWasDeleting($category));
        $category->posts()->detach();
        return $category->forceDelete();
    }

    public function trashedFind($id)
    {
        return $this->newQueryBuilder()->withTrashed()->find($id);
    }

    public function findBySlug($slug)
    {
        return $this->newQueryBuilder()->whereHas('translations', function ($query) use ($slug) {
            $query->where('slug', '=', $slug);
        })->first();
    }

    public function serverPagingFor(Request $request, $relations = null)
    {
        $query = $this->newQueryBuilder();
        if ($relations) {
            $query = $query->with($relations);
        }

        if ($request->get('search') !== null) {
            $keyword = $request->get('search');
            $query->where(function ($query) use ($keyword) {
                $query->whereHas('translations', function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                    $query->orWhere('slug', 'LIKE', "%{$keyword}%");
                })
                    ->orWhere('id', $keyword);
            });
        }

        if ($request->get('is_trashed') !== null) {
            $query->onlyTrashed();
        }

        if ($request->get('name') !== null) {
            $name = $request->get('name');
            $query->whereHas('translations', function ($query) use ($name) {
                $query->where('name', 'LIKE', "%{$name}%");
            });
        }

        if ($request->get('status') !== null) {
            $status = $request->get('status');
            $query->where('status', '=', $status);
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            if (Str::contains($request->get('order_by'), '.')) {
                $fields = explode('.', $request->get('order_by'));

                $query->with('translations')->join('blog__category_translations as t', function ($join) {
                    $join->on('blog__categories.id', '=', 't.category_id');
                })
                    ->where('t.locale', \App::getLocale())
                    ->groupBy('blog__categories.id')->orderBy("t.{$fields[1]}", $order);
            } else {
                $query->orderBy($request->get('order_by'), $order);
            }
        } else {
            $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
        }

        return $query->paginate($request->get('per_page', 10));
    }

    public function getTree($pid = 0, $status = null, $maxDepth = null)
    {
        $menuItems = Category::query();
        if ($status !== null) {
            $menuItems->where('status', '=', $status);
        }
        $menuItems = $menuItems->orderBy('order', 'asc')->orderBy('created_at', 'desc')->get();
        $tree = array();
        if ($menuItems && $menuItems->isNotEmpty()) {
            $parents = [0];
            $children = [];
            $terms = [];
            foreach ($menuItems as $key => $category) {
                $parents[$category->id][] = $category->pid;
                $children[$category->pid][] = $category->id;
                $terms[$category->id] = $category;
            }
            $maxDepth = (!isset($maxDepth)) ? count($children) : $maxDepth;

            // Keeps track of the parents we have to process, the last entry is used
            // for the next processing step.
            $process_parents = array();
            $process_parents[] = $pid;

            // Loops over the parent terms and adds its children to the tree array.
            // Uses a loop instead of a recursion, because it's more efficient.
            try {
                while (count($process_parents)) {
                    $parent = array_pop($process_parents);
                    // The number of parents determines the current depth.
                    $depth = count($process_parents);
                    if ($maxDepth > $depth && !empty($children[$parent])) {
                        $has_children = false;
                        $child = current($children[$parent]);
                        do {
                            if (empty($child)) {
                                break;
                            }
                            $term = $terms[$child];
                            if (isset($parents[$term->id])) {
                                // Clone the term so that the depth attribute remains correct
                                // in the event of multiple parents.
                                $term = clone $term;
                            }
                            $term->depth = $depth;
                            $term->parentIds = $parents[$term->id];
                            $tree[] = $term;
                            if (!empty($children[$term->id])) {
                                $has_children = true;

                                // We have to continue with this parent later.
                                $process_parents[] = $parent;
                                // Use the current term as parent for the next iteration.
                                $process_parents[] = $term->id;

                                // Reset pointers for child lists because we step in there more often
                                // with multi parents.
                                reset($children[$term->id]);
                                // Move pointer so that we get the correct term the next time.
                                next($children[$parent]);
                                break;
                            }
                        } while ($child = next($children[$parent]));

                        if (!$has_children) {
                            // We processed all terms in this hierarchy-level, reset pointer
                            // so that this function works the next time it gets called.
                            reset($children[$parent]);
                        }
                    }
                }
            } catch (\Exception $e) {
                dd($e->getMessage(), $children, $parent);
            }
        }

        return $tree;
    }

    public function getCategories()
    {
        $categories = ['0' => trans('blog::category.labels.Select parent')];
        $items = array_map(function ($item) {
            $item->name = ($item->depth ? str_repeat("-", $item->depth) . " " : '') . $item->name;
            return $item;
        }, $this->getTree());
        foreach ($items as $item) {
            $categories[$item->id] = $item->name;
        }
        return $categories;
    }
}
