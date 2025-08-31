<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use  App\Repositories\IRoleRepository;
use  App\Repositories\IPermissionRepository;
use Illuminate\Http\Request;
use App\Repositories\IUserRepository;
use Illuminate\Mail\Attachment;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->usersRepository->getWhereIn('user_type'  , [2 , 3] );
 
        return view('dashboard.users.index' , compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['permissions'] = $this->permissionRepository->getAll();

        return view('dashboard.users.create')->with([

            'data' => $data

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        if($request->has('image')){

            $data['image'] = $request->file('image')->store('dashboard/uploads');
        }

        $user = $this->usersRepository->create($data);

        if($user){
            $ids = explode(',', $request->permission_id);
            $permissions = $this->permissionRepository->getWhereIn('id',$ids)->pluck('name');
           
            if(count($permissions) > 0){
                
                $user->givePermissionTo($permissions);
            }
            

            session()->flash('success', trans('admin.success', ['field' => __('admin.addition')]));

        }
        else{

            session()->flash('error', trans('admin.error', ['field' => __('admin.addition')]));

        }

        return  response()->json(['success' => trans('admin.added_success' , ['field' => __('admin.user')]) , 200]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['user'] = $this->usersRepository->findOne($id);

        return view('dashboard.users.show')->with([

            'data' => $data

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->usersRepository->findOne($id);
        return view('dashboard.users.edit' , compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('permission_id','_method');

        $user = $this->usersRepository->findOne($id);
    
        $updated = $this->usersRepository->update($data, $id);

        if($updated){

            $ids = explode(',', $request->permission_id);
            $permissions = $this->permissionRepository->getWhereIn('id',$ids)->pluck('name');
           
            if(count($permissions) > 0){
                
                $user->syncPermissions($permissions);
            }
            

            session()->flash('success', trans('admin.success', ['field' => __('admin.edition')]));

        }
        else{

            session()->flash('error', trans('admin.error', ['field' => __('admin.edition')]));

        }

        return  response()->json(['success' => trans('admin.updated_success' , ['field' => __('admin.user')]) , 200]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $this->usersRepository->forceDelete($id);

      return  response()->json(['success' => trans('admin.deleted_success' , ['field' => __('admin.user')]) ,200]);

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

    public function userAttachemts($userId)
    {
        $user = $this->usersRepository->findOne($userId);
        return view('dashboard.users.attachments' , compact('user'));

    }
}