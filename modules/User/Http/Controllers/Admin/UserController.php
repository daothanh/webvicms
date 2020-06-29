<?php

namespace Modules\User\Http\Controllers\Admin;

use Arr;
use Modules\User\Notifications\SendPasswordNotification;
use Modules\User\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
use Modules\Core\Http\Controllers\BaseAdminController;

class UserController extends BaseAdminController
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $this->seo()->setTitle(__('user::user.Users'));
        $this->breadcrumb->addItem(__('user::user.Users'));
        return $this->view('user::admin.user.index');
    }

    public function create()
    {
        $this->seo()->setTitle(__('user::user.title.Create a user'));
        $this->breadcrumb->addItem(__('user::user.Users'), route('admin.user.index'));
        $this->breadcrumb->addItem(__('user::user.title.Create a user'));
        return $this->view('user::admin.user.create');
    }

    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            abort(404);
        }
        $this->seo()->setTitle(__('user::user.title.Edit a user'));
        $this->breadcrumb->addItem(__('user::user.Users'), route('admin.user.index'));
        $this->breadcrumb->addItem(__('user::user.title.Edit a user'));
        return $this->view('user::admin.user.edit', compact('user'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'user.name'=> 'required|string'
        ];
        if (Arr::get($data,'user.id') !== null) {
            $rules['user.email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore(Arr::get($data,'user.id'), 'id')
            ];
            if ($request->get('password') !== null) {
                $rules['password'] = 'required|confirmed';
            }
        } else {
            $rules['user.email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')
            ];
            $rules['password'] = 'required|confirmed';
        }

        $validator = \Validator::make($data, $rules);
        $validator->setAttributeNames([
            'user.name' => __('Name'),
            'user.email' => __('E-Mail Address'),
            'password' => __('Password'),
        ]);
        if ($validator->fails()) {
            if (Arr::get($data,'user.id') !== null) {
                return redirect()->route('admin.user.edit', ['id' => Arr::get($data,'user.id')])
                    ->withInput()
                    ->withErrors($validator->messages());
            }
            return redirect()->route('admin.user.create')->withInput()->withErrors($validator->messages());
        }

        $userData = $request->get('user');
        if ($request->get('medias_single') !== null) {
            $userData['medias_single'] = $request->get('medias_single');
        }
        if (!isset($userData['activated'])) {
            $userData['activated'] = 0;
        }
        if (Arr::get($data,'user.id') !== null) {
            $user = $this->userRepository->find(Arr::get($data,'user.id'));
            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }
            $this->userRepository->update($user, $userData);
            $msg = __('User was updated!', ['email' => $user->email]);
        } else {
            $userData['password'] = Hash::make($data['password']);
            $user = $this->userRepository->create($userData);
            event(new Registered($user));
            $user->notify(new SendPasswordNotification($data['password']));
            $msg = __('User was created!', ['email' => $user->email]);
        }
        if ($request->get('roles') !== null) {
            $roles = $request->get('roles');
            $user->syncRoles(array_values($roles));
        }
        return redirect()->route('admin.user.index')->withSuccess($msg);
    }
}
