<?php
namespace Modules\User\Http\Controllers\Api;

use Modules\User\Repositories\UserRepository;
use Modules\Core\Http\Controllers\ApiController;
use Modules\User\Transformers\ApiUserTransformer;
use Illuminate\Http\Request;

class UserController extends ApiController {
    protected $user;
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function index(Request $request)
    {
        if ($request->get('columns') !== null) { // For datatables.net
            $users = $this->user->serverPagingFor($this->convertDataTableParams($request), ['roles']);
            $output = [
                "draw" => $request->get('draw'),
                "recordsTotal" => $users->total(),
                "recordsFiltered" => $users->total(),
                'data' => ApiUserTransformer::collection($users)
            ];
            return response()->json($output);
        }
        return ApiUserTransformer::collection($this->user->serverPagingFor($request, ['roles']));
    }

    public function delete($id) {
        $user = $this->user->find($id);
        $currentUser = $this->guard()->user();
        if ($user->id !== $currentUser->id) {
            $this->user->destroy($user);
        } else {
            return response()->json(['error' => "You can't delete own!"]);
        }
        return response()->json(['error' => false]);
    }

    public function deleteMultiple(Request $request) {
        $ids = $request->get('ids');
        $users = $this->user->newQueryBuilder()
            ->whereIn('id', $ids)
            ->get();
        if ($users->isNotEmpty()) {
            $currentUser = $this->guard()->user();
            foreach ($users as $user) {
                if ($user->id !== $currentUser->id) {
                    $this->user->destroy($user);
                }
            }
        }

        return response()->json(['error' => false]);
    }
}
