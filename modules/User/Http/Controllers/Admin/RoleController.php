<?php
namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Support\Arr;
use Modules\User\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Core\Http\Controllers\BaseAdminController;

class RoleController extends BaseAdminController {
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository) {
        parent::__construct();
        $this->roleRepository = $roleRepository;
    }

    public function index() {
        $this->seo()->setTitle(__('Roles'));
        $this->breadcrumb->addItem(__('Roles'));
        return $this->view('user::admin.role.index');
    }

    public function create() {
        $this->seo()->setTitle(__('Create a role'));
        $this->breadcrumb->addItem(__('Roles'), route('admin.role.index'));
        $this->breadcrumb->addItem(__('Create a role'));
        return $this->view('user::admin.role.create');
    }

    public function edit($id) {
        $role = $this->roleRepository->find($id);
        $this->seo()->setTitle(__('Update a role'));
        $this->breadcrumb->addItem(__('Roles'), route('admin.role.index'));
        $this->breadcrumb->addItem(__('Update a role'));
        return $this->view('user::admin.role.edit', compact('role'));
    }

    public function store(Request $request) {
        $data = $request->all();

        $rules = [
            'role.name' => ['required', Rule::unique('roles', 'name')]
        ];

        if (Arr::get($data, 'role.id')) {
            $rules['role.name'] = [
                'required',
                Rule::unique('roles', 'name')->ignore(Arr::get($data, 'role.id')),
            ];
        }

        $validator = \Validator::make($data, $rules);
        $validator->setAttributeNames(['role.name' => __('Role Name')]);

        if ($validator->fails()) {
            if (Arr::get($data, 'role.id')) {
                return redirect()->route('admin.role.edit', ['id' => Arr::get($data, 'role.id')])->withInput()->withErrors($validator->messages());
            }
            return redirect()->route('admin.role.create')->withInput()->withErrors($validator->messages());
        }
        $msg = 'Role was created!';
        $roleData = Arr::get($data, 'role');
        if (Arr::get($data, 'role.id')) {
            $role = $this->roleRepository->find(Arr::get($data, 'role.id'));
            $this->roleRepository->update($role, $roleData);
            $msg = "Role was updated!";
        } else {
            $role = $this->roleRepository->create($roleData);
        }
        $permissions = Arr::get($data, 'permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->route('admin.role.index')->withSuccess($msg);
    }
}


