<?php
namespace Modules\User\Http\Controllers\Api;

use Modules\User\Repositories\RoleRepository;
use Modules\Core\Http\Controllers\ApiController;
use Modules\User\Transformers\ApiRoleTransformer;
use Illuminate\Http\Request;

class RoleController extends ApiController
{
    protected $roleRepository;
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index(Request $request)
    {
        if ($request->get('columns') !== null) {
            // For datatables.net
            $users  = $this->roleRepository->serverPagingFor($this->convertDataTableParams($request));
            $output = [
                "draw"            => $request->get('draw'),
                "recordsTotal"    => $users->total(),
                "recordsFiltered" => $users->total(),
                'data'            => ApiRoleTransformer::collection($users),
            ];
            return response()->json($output);
        }
        return ApiRoleTransformer::collection($this->roleRepository->serverPagingFor($request, ['roles']));
    }

    public function delete($id)
    {
        $role        = $this->roleRepository->find($id);
        $currentUser = $this->guard()->user();
        if ($role->name === 'admin') {
            return response()->json(['error' => "You can't delete admin role!"]);
        }
        if ($role->name === 'user') {
            return response()->json(['error' => "You can't delete user role!"]);
        }
        $this->roleRepository->destroy($role);
        return response()->json(['error' => false]);
    }
}
