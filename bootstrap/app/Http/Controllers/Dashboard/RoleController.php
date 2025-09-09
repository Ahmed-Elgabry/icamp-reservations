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
        \Log::info('Role update started', [
            'role_id' => $id,
            'request_data' => $request->all(),
            'has_permissions' => $request->has('permissions'),
            'permissions_count' => $request->has('permissions') ? count($request->permissions) : 0
        ]);

        $data = $request->validated();

        // Check if role name already exists
        if (
            Role::where('nickname_ar', $data['nickname_ar'])->where('id', '!=', $id)->exists() ||
            Role::where('nickname_en', $data['nickname_en'])->where('id', '!=', $id)->exists()
        ) {
            return response()->json(['message' => 'Role name already exists'], 409);
        }

        $data['name'] = str_replace(' ', '-', $request->nickname_en);

        \Log::info('Updating role basic data', ['data' => $data]);
        $this->rolesRepository->update($data, $id);

        $role = $this->rolesRepository->findOne($id);
        \Log::info('Role found after update', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'current_permissions' => $role->permissions->pluck('name')->toArray()
        ]);

        // Check if permissions exist before syncing
        if ($request->has('permissions') && is_array($request->permissions)) {
            \Log::info('Syncing permissions', ['permissions' => $request->permissions]);
            
            // Log customer permissions specifically
            $customerPermissions = array_filter($request->permissions, function($perm) {
                return strpos($perm, 'customers.') === 0;
            });
            \Log::info('Customer permissions in request', ['customer_permissions' => $customerPermissions]);
            
            // Clear all permissions first, then sync new ones
            $role->permissions()->detach();
            \Log::info('All permissions detached for role', ['role_id' => $role->id]);
            
            $role->syncPermissions($request->permissions);

            // Verify permissions were synced
            $role->refresh();
            $finalPermissions = $role->permissions->pluck('name')->toArray();
            $finalCustomerPermissions = array_filter($finalPermissions, function($perm) {
                return strpos($perm, 'customers.') === 0;
            });
            
            \Log::info('Permissions after sync', [
                'all_synced_permissions' => $finalPermissions,
                'customer_permissions_after_sync' => $finalCustomerPermissions
            ]);
        } else {
            \Log::warning('No permissions to sync - clearing all permissions', [
                'has_permissions' => $request->has('permissions'),
                'permissions_data' => $request->get('permissions')
            ]);
            
            // If no permissions sent, clear all
            $role->permissions()->detach();
            \Log::info('All permissions cleared for role', ['role_id' => $role->id]);
        }

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
