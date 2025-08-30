<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\IRoleRepository;
use App\Repositories\IPermissionRepository;
use App\Requests\dashboard\CreateUpdateRoleRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Traits\Roles;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private $rolesRepository;
    private $permissionRepository;
    use Roles;

    public function __construct(IRoleRepository $rolesRepository, IPermissionRepository $permissionRepository)
    {
        $this->rolesRepository = $rolesRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index()
    {
        $roles = $this->rolesRepository->getWhere([['id', '>', 1]]);
        return view('dashboard.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = $this->permissionRepository->getAll();
        $html = $this->addRole();
        return view('dashboard.roles.create', compact('permissions', 'html'));
    }

    public function store(CreateUpdateRoleRequest $request)
    {
        $data = $request->except('permissions');

        // Check if role name already exists
        if (Role::where('nickname_ar', $data['nickname_ar'])->exists() || Role::where('nickname_en', $data['nickname_en'])->exists()) {
            return response()->json(['message' => 'Role name already exists'], 409);
        }

        $data['name'] = str_replace(' ', '-', $request->nickname_en);

        // Create the role
        $role = $this->rolesRepository->create($data);

        // Check if permissions exist before syncing
        if ($request->has('permissions') && is_array($request->permissions)) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['message' => 'Role created successfully']);
    }

    public function edit($id)
    {
        $role = $this->rolesRepository->findOne($id);
        $html = $this->editRole($id);
        return view('dashboard.roles.edit', compact('role', 'html'));
    }

    public function update(CreateUpdateRoleRequest $request, $id)
    {
        $data = $request->validated();

        // Check if role name already exists
        if (Role::where('nickname_ar', $data['nickname_ar'])->where('id', '!=', $id)->exists() ||
            Role::where('nickname_en', $data['nickname_en'])->where('id', '!=', $id)->exists()) {
            return response()->json(['message' => 'Role name already exists'], 409);
        }

        $data['name'] = str_replace(' ', '-', $request->nickname_en);
        $this->rolesRepository->update($data, $id);
        $role = $this->rolesRepository->findOne($id);
        $role->syncPermissions($request->permissions);

        return response()->json(['message' => 'Role updated successfully']);
    }

    public function destroy($id)
    {
        $this->rolesRepository->forceDelete($id);
        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function deleteAll(Request $request)
    {
        $requestIds = json_decode($request->data);
        $ids = [];

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }

        if ($this->rolesRepository->deleteForceWhereIn('id', $ids)) {
            return response()->json(['message' => 'Roles deleted successfully']);
        } else {
            return response()->json(['message' => 'Failed to delete roles'], 500);
        }
    }
}
