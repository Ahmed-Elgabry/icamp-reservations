<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserIntrestsResource;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use App\Repositories\ICategoryRepository;
use App\Requests\Api\Auth\ResetPasswordRequest;
use App\Traits\ApiResponseTrait;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use App\Requests\Api\Auth\UpdateProfileRequest;

class UserController extends Controller {

    use ApiResponseTrait,  UploadTrait;

    public function __construct(User $model , ICategoryRepository $Icategory)
    {
        $this->model     = $model;
        $this->Icategory = $Icategory;
        $this->data      = [];
    }


    public function resetPassword(ResetPasswordRequest $request)
    {
        if(!auth()->user()){
            return $this->failMessage(__('auth.failed'));
        }

        auth()->user()->update(['password' => $request->password]);
        $requestToken = ltrim($request->header('authorization'), 'Bearer ');
        $userData     = UserResource::make(auth()->user()->refresh())->setToken($requestToken);
        return $this->ApiResponse(['user' => $userData], __('apis.data_fetched'), 200);
    }


    public function updateProfile(UpdateProfileRequest $request) {
        $user = auth()->user();
        $attributes = $request->validated();
        $request->user_type == "user" ? $attributes['user_type'] = 2 :  $attributes['user_type'] = 3;
        $user->update($attributes);
        $requestToken = ltrim($request->header('authorization'), 'Bearer ');
        $userData     = UserResource::make($user->refresh())->setToken($requestToken);
        return $this->ApiResponse(['user' => $userData], __('apis.updated'), 200);
    }



    public function userIntrests()
    {
       $intrests = $this->Icategory->getAllActive(['usercategories' , 'usercategories.category']);
     
       $this->data['intrests'] = UserIntrestsResource::collection($intrests);

       return $this->ApiResponse($this->data , __('apis.data_fetched'), 200);
 
    }

    public function updateUserIntrests(Request $request)
    {
       dd($request->all());
    }

    

}