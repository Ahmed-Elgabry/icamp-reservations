<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UpdateProfileRequest;
use App\Requests\Api\Auth\ActivateRequest;
use App\Requests\Api\Auth\LoginWithoutPasswordRequest;
use App\Requests\Api\Auth\PromoteProfileRequest;
use App\Requests\Api\Auth\UserCompleteDataRequest;
use App\Requests\Api\Auth\SendCodeRequest;

use App\Http\Resources\Api\Notifications\NotificationsCollection;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use App\Requests\Api\Auth\ActivateByEmailRequest;
use App\Requests\Api\Auth\LoginRequest;
use App\Requests\Api\Auth\RegisterRequest;
use App\Requests\Api\Auth\SendEmailCodeRequest;
use App\Traits\ApiResponseTrait;
use App\Traits\SmsTrait;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller {
    use ApiResponseTrait, SmsTrait, UploadTrait;

   
    //Auth without password
    public function sendCode(SendCodeRequest $request) {

        $user = User::where('phone', $request['phone'])->first();

        if ($user) {

            $user->sendVerificationCode();
           return $this->ApiResponse(null , __('auth.code_re_send') , 200 );

        } else {

            $user = User::create($request->all());
            $user->sendVerificationCode();
            return $this->ApiResponse(null,  __('auth.code_sent'), 200);

        }

    }


    public function activate(ActivateRequest $request) {
        $user = User::where('phone', $request['phone'])->first();

        if (!$this->isCodeCorrect($user, $request->code)) {
            return $this->ApiResponse(null , trans('auth.code_invalid') , 400);
        }

        return $this->ApiResponse(['user' => $user->markAsActive()->login()] , __('auth.activated'), 200 );
    }

    public function loginWithoutPassword(LoginWithoutPasswordRequest $request) {
        $user = User::where('phone', $request['phone'])->first();

        if (!$this->isCodeCorrect($user, $request->code)) {
            return $this->ApiResponse(null , trans('auth.code_invalid') , 400);
        }

        if ($user->is_blocked) {
            return $this->ApiResponse($user , __('auth.blocked_user') , 400);
        }

        return $this->ApiResponse(['user' => $user->markAsActive()->login()] , __('apis.signed') , 200);
    }

    //end auth without password



    /*
    |--------------------------------------------------------------------------
    | auth email & password
    |--------------------------------------------------------------------------
    |*/
     

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->all());
        $user->sendVerificationMail();

        return $this->ApiResponse(new UserResource($user),  __('auth.code_sent'), 200);

    }


    public function login(LoginRequest $request)
    {
        if(!$user = User::where('email', $request['email'])->first())
        {
      
         return $this->ApiResponse(__('auth.failed'));
        }

        if (!Hash::check($request->password, $user->password)) 
        {
          return $this->ApiResponse(__('auth.failed'));
        }

        if ($user->is_blocked) 
        {
          return $this->blockedReturn($user);
        }

        if (!$user->is_approved) 
        {
          return $this->NotApprovedReturn($user);
        }

        if (!$user->is_active) 
        {
          return $this->phoneActivationReturn($user);
        }

        return $this->ApiResponse(['user' => $user->login()] , __('apis.signed'), 200 );

    }


    public function sendEmailCode(SendEmailCodeRequest $request) {

        $user = User::where('email', $request['email'])->first();

        if (!$user) {

            return $this->ApiResponse(null,  __('auth.no_user_found'), 400);

        } else {

            $user->sendVerificationMail();
            return $this->ApiResponse(null , __('auth.code_re_send') , 200 );
        }

    }


    public function activateByEmailCode(ActivateByEmailRequest $request) {
        $user = User::where('email', $request['email'])->first();

        if (!$this->isCodeCorrect($user, $request->code)) {
            return $this->ApiResponse(null , trans('auth.code_invalid') , 400);
        }

        return $this->ApiResponse(['user' => $user->markAsActive()->login()] , __('auth.activated'), 200 );
    }

    /*
    |--------------------------------------------------------------------------
    | end auth email & password
    |--------------------------------------------------------------------------
    |*/



    public function completeDate(UserCompleteDataRequest $request) {
        $user = User::create($request->validated());
        $user->sendVerificationCode();
        $userData = new UserResource($user->refresh());
        return $this->ApiResponse(['user' => $userData] , __('auth.registered') , 200);
    }


    public function promoteProfile(PromoteProfileRequest $request)
    {
        $user = auth()->user();
        $user->update($request->validated() + ['is_approved' => 0 , 'user_type' => 3 ]);
        $requestToken = ltrim($request->header('authorization'), 'Bearer ');
        $userData     = UserResource::make($user->refresh())->setToken($requestToken);
        return $this->ApiResponse(['user' => $userData] ,  __('apis.updated') , 200);
        
    }


    public function isCodeCorrect($user = null, $code): bool {
        if (!$user
          || $code != $user->code
          //|| $user->code_expire->isPast()
          //|| env('RESET_CODE') != $code
        ) {
          return false;
        }
        return true;
    }
    

    public function logout(Request $request) {
        if ($request->bearerToken()) {
            $user = Auth::guard('sanctum')->user();
            if ($user) {
                $user->logout();
            }
        }

        return $this->ApiResponse( null , __('apis.loggedOut') , 200);
    }

    public function getProfile(Request $request) {
        $user         = auth()->user();
        $requestToken = ltrim($request->header('authorization'), 'Bearer ');
        $userData     = UserResource::make($user)->setToken($requestToken);
        return $this->ApiResponse(['user' => $userData] , null , 200);
    }

    public function changeLang(Request $request) {
        $user = auth()->user();
        $lang = in_array($request->lang, languages()) ? $request->lang : 'ar';
        $user->update(['lang' => $lang]);
        App::setLocale($lang);
        return $this->ApiResponse(null , __('apis.updated') , 200);
    }

    public function switchNotificationStatus() {
        $user = auth()->user();
        $user->update(['is_notify' => !$user->is_notify]);
        return $this->ApiResponse(['notify' => (bool)$user->refresh()->is_notify] , __('apis.updated'), 200);
    }

    public function getNotifications() {
        auth()->user()->unreadNotifications->markAsRead();
        $notifications = new NotificationsCollection(auth()->user()->notifications()->paginate($this->paginateNum()));
        return $this->ApiResponse(['notifications' => $notifications] , __('apis.updated'), 200);
    }

    public function countUnreadNotifications() {
        return $this->ApiResponse(['count' => auth()->user()->unreadNotifications->count()] , null , 200);
    }

    public function deleteNotification($notification_id) {
        auth()->user()->notifications()->where('id', $notification_id)->delete();
        return $this->ApiResponse(null , __('site.notify_deleted') , 200);
    }

    public function deleteNotifications() {
        auth()->user()->notifications()->delete();
        return $this->ApiResponse(null , __('apis.deleted') , 200);
    }

 
}