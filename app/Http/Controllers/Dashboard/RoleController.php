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
use Spatie\Permission\Models\Role; // تأكد من استيراد نموذج Role

class RoleController extends Controller
{
    private $rolesRepository;
    private $permissionRepository; // إضافة المتغير الخاص بالمستودع
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

        // تحقق من وجود اسم الدور مسبقًا
        if (Role::where('name', $data['nickname_ar'])->exists() || Role::where('name', $data['nickname_en'])->exists()) {
            return response()->json(['message' => 'اسم الدور موجود مسبقًا'], 409);
        }

        $data['name'] = str_replace(' ', '-', $request->nickname_en);

        // إنشاء الدور
        $role = $this->rolesRepository->create($data);
        $role->syncPermissions($request->permissions);

        return response()->json(['message' => 'تم إنشاء الدور بنجاح']);
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

        // تحقق من وجود اسم الدور مسبقًا
        if (Role::where('name', $data['nickname_ar'])->where('id', '!=', $id)->exists() || 
            Role::where('name', $data['nickname_en'])->where('id', '!=', $id)->exists()) {
            return response()->json(['message' => 'اسم الدور موجود مسبقًا'], 409);
        }

        $data['name'] = str_replace(' ', '-', $request->name_en);
        $this->rolesRepository->update($data, $id);
        $role = $this->rolesRepository->findOne($id);
        $role->syncPermissions($request->permissions);
        
        return response()->json(['message' => 'تم تحديث الدور بنجاح']);
    }

    public function destroy($id)
    {
        $this->rolesRepository->forceDelete($id);
        return response()->json(['message' => 'تم حذف الدور بنجاح']);
    }

    public function deleteAll(Request $request)
    {
        $requestIds = json_decode($request->data);
        $ids = [];

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }

        if ($this->rolesRepository->deleteForceWhereIn('id', $ids)) {
            return response()->json(['message' => 'تم حذف الأدوار بنجاح']);
        } else {
            return response()->json(['message' => 'فشل في حذف الأدوار'], 500);
        }
    }
}
