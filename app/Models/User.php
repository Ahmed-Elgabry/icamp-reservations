<?php

namespace App\Models;

use App\Http\Resources\Api\UserResource;
use App\Traits\EmailTrait;
use App\Traits\SmsTrait;
use App\Traits\UploadTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , HasRoles , SmsTrait , UploadTrait , EmailTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'country_code',
        'phone',
        'email',
        'password',
        'image',
        'is_active',
        'is_blocked',
        'is_approved',
        'lang',
        'is_notify',
        'code',
        'code_expire',
        'lat',
        'lng',
        'address',
        'wallet_balance',
        'city_id',
        'user_type', 
        'bio', 
        'experience',
        'university',
        'graduation_year',
        'qualification',
        'job'

        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    
    public function isAdmin(){
        return $this->user_type === 1;
    }

    public function isUser(){
        return $this->user_type === 2;
    }

    public function isProvider(){
        return $this->user_type === 3;
    }
    
    public function isActive(){
        return $this->is_active === 1;
    }

    public function scopeProviders($query)
    {
       return $query->where(['is_active' => 1 , 'user_type' => 3]);
    }

    public function scopeAdmins($query)
    {
       return $query->where(['is_active' => 1 , 'user_type' => 1]);
    }

    // public function setImageAttribute($value)
    // {
    //     if($value){
    //         if(auth()->user()->image)
    //         Storage::delete(auth()->user()->getRawOriginal('image'));


    //       return $this->attributes['image'] = $this->StoreFile('users' , $value);
    //     }
    // }
    

    public function roleName()
    {
        return $this->roles->first() ? $this->roles->first()->nickname_ar : '';
    }

    public function roleNameEn()
    {
        return $this->roles->first() ? $this->roles->first()->nickname_en : '';
    }

    public function getImageAttribute($value)
    {
        if($value)
        {
            return asset('storage/'.$value);
        }
        return null;
    }

    public function getFullPhoneAttribute()
    {
        return $this->attributes['country_code'] . $this->attributes['phone'];
    }

    public function getNameAttribute()
    {
        return $this->attributes['first_name'] ." ". $this->attributes['last_name'];
    }



    public function setPasswordAttribute($value)
    {
        if($value)
        return $this->attributes['password'] = bcrypt($value);
    }


    
    public function markAsActive()
    {
        $this->update(['code' => null, 'code_expire' => null, 'is_active' => true]);
        return $this;
    }

    public function sendVerificationCode()
    {
        $this->update([
            'code'        => $this->activationCode(),
            'code_expire' => Carbon::now()->addMinute(),
        ]);

        $msg = trans('api.activeCode');
        $this->sendSms($this->full_phone, $msg . $this->code);
        return ['user' => new UserResource($this->refresh())];
    }


    public function sendVerificationMail()
    {
      
        $this->update([
            'code'        => $this->activationCode(),
            'code_expire' => Carbon::now()->addMinute(),
        ]);

        $msg = trans('apis.activeCode');
        $this->sendMail($this->email, $msg . $this->code);
        return ['user' => new UserResource($this->refresh())];
   
    }


    private function activationCode()
    {
        return 1234;
        return mt_rand(1111, 9999);
    }

    public function logout()
    {
        $this->tokens()->delete();
        if (request()->device_id) {
            $this->devices()->where(['device_id' => request()->device_id])->delete();
        }
        return true;
    }

    public function devices()
    {
        return $this->morphMany(Device::class, 'morph');
    }

    public function categories()
    {
        return $this->hasMany(UserCategory::class);
    }


    public function rates()
    {
        return $this->morphMany(Rate::class , 'ref');
    }

    public function login()
    {
        $this->updateUserDevice();
        $this->updateUserLang();
        $token = $this->createToken(request()->device_type)->plainTextToken;
        return UserResource::make($this)->setToken($token);
    }

    public function updateUserLang()
    {
        if (request()->header('Lang') != null
            && in_array(request()->header('Lang'), languages())) {
            $this->update(['lang' => request()->header('Lang')]);
        } else {
            $this->update(['lang' => defaultLang()]);
        }
    }

    public function updateUserDevice()
    {
        if (request()->device_id) {
            $this->devices()->updateOrCreate([
                'device_id'   => request()->device_id,
                'device_type' => request()->device_type,
            ]);
        }
    }



}
