<?php
namespace Modules\User\Http\Controllers\Api;

use Modules\User\Repositories\UserRepository;
use Modules\Core\Http\Controllers\ApiController;
use Modules\User\Transformers\ApiUserTransformer;
use Illuminate\Http\Request;

class ProfileController extends ApiController {
    protected $user;
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function index(Request $request)
    {

        return new ApiUserTransformer($this->guard()->user());
    }

    public function settings(Request $request) {
        $user = $this->guard()->user();
        $data = $request->all();

        foreach ($data as $key => $val) {
            \Settings::set('user_'.$user->id, $key, $val);
        }
        return response()->json($data);
    }
}
