<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use  App\Repositories\IRoleRepository;
use  App\Repositories\IPermissionRepository;
use Illuminate\Http\Request;
use App\Repositories\IUserRepository;
use App\Requests\dashboard\CreateUpdateAdminRequest;
use App\Requests\dashboard\editProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use File;

class AdminController extends Controller
{
    private $usersRepository;
    private $rolesRepository;
    private $permissionRepository;

    public function __construct(IUserRepository $usersRepository,
                                IRoleRepository $rolesRepository,
                                IPermissionRepository $permissionRepository){

        $this->usersRepository = $usersRepository;
        $this->rolesRepository = $rolesRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = $this->usersRepository->getAll();

        return view('dashboard.admins.index' , compact('users'));
    }

    public function create()
    {
        $roles = $this->rolesRepository->getAll();
        return view('dashboard.admins.create' , compact('roles'));
    }

    public function store(CreateUpdateAdminRequest $request)
    {
        try {
            \DB::beginTransaction();
            $data = $request->all();
            $data['user_type'] = 1 ;
            $data['is_active'] = $request->is_active == 'on' ? '1' : '0';

            if($request->has('image')){
                $data['image'] = $request->file('image')->store('dashboard/uploads');
            }
            $user = $this->usersRepository->create($data);

            if($user && $request->role_id){
                $role = $this->rolesRepository->findOne($request->role_id);

                if($role){

                    $user->assignRole($role->name);
                }

                $user->syncPermissions($role->permissions()->pluck('name')->toArray());
            //  $updateRole = $this->usersRepository->update(['user_type' =>  $request->role_id], $user->id);
            }
            \DB::commit();
            return response()->json();
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error creating admin: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function show($id)
    {
        $data['user'] = $this->usersRepository->findOne($id);

        return view('dashboard.admins.show')->with([

            'data' => $data

        ]);
    }

    public function edit($id)
    {
        $roles = $this->rolesRepository->getAll();
        $user = $this->usersRepository->findWith($id , ['roles']);


        return view('dashboard.admins.create' , compact('user' , 'roles'));

    }

    public function adminPermissions($id)
    {
        $permissions = $this->permissionRepository->getAll();
        $admin = $this->usersRepository->findWith($id , ['roles']);
        return view('dashboard.admins.permissions' , compact('admin' , 'permissions'));

    }

    public function update(CreateUpdateAdminRequest $request, $id)
    {
        $data = $request->except('role_id','_method');

        $user = $this->usersRepository->findOne($id);
        $data['is_active'] = $request->is_active == 'on' ? '1' : '0';
        if($request->has('image')){
            $data['image'] = $request->file('image')->store('dashboard/uploads');
        }

        $updated = $this->usersRepository->update($data, $id);

        if($updated && $request->role_id){
            $role = $this->rolesRepository->findOne($request->role_id);


            if($role){
                $user->syncRoles([$role->name]);
            }

            $user->syncPermissions($role->permissions()->pluck('name')->toArray());
         //   $updateRole = $this->usersRepository->update(['user_type' =>  $request->role_id], $user->id);
        }


         return response()->json();
    }

    public function activationStatus($id)
    {

        $admin = $this->usersRepository->findOne($id);

        if($admin->is_active)
        {
            $this->usersRepository->update(['is_active' => 0], $id);
            session()->flash('success', "عملية ناجحة");
            return back();
        }else{
            $this->usersRepository->update(['is_active' => 1], $id);
            session()->flash('success', "عملية ناجحة");
            return back();
        }
    }

    public function destroy($id)
    {
         $this->usersRepository->forceDelete($id);
        return response()->json('success');
    }



    public function deleteAll(Request $request) {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
          $ids[] = $id->id;
        }
        if ($this->usersRepository->deleteForceWhereIn('id', $ids)) {
          return response()->json('success');
        } else {
          return response()->json('failed');
        }
    }



    public function editProfile()
    {
       return view('dashboard.admins.edit-profile');
    }

    public function updateProfile(editProfileRequest $request)
    {
        $user = Auth::user();
        $requestData = $request->all();
        if($request->password === null || trim($request->password) === ''){
            unset($requestData['password']);
            unset($requestData['password_confirmation']);
            $this->usersRepository->update( $requestData , $user->id);
            return response()->json();
        } else if($request->password !== null || trim($request->password) !== ''){
            $this->usersRepository->update( $requestData , $user->id);
            Auth::logout();
            return response()->json(['logout' => true], 200);
        }
    }


    public function fileStorageServe($filePath)
    {

        if(auth()->check())
        {
            $file = public_path().DIRECTORY_SEPARATOR .'storage'.DIRECTORY_SEPARATOR .str_replace('/',DIRECTORY_SEPARATOR,$filePath);

            return response()->file($file);
        }
        return redirect(route('home'));
    }

}
