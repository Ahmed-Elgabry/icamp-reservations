<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SMS;
use App\Requests\dashboard\UpdateSettingRequest;
use App\Services\SettingService;
use Illuminate\Http\Request;
use App;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    private $modal;

    public function __construct(Setting $modal){

        $this->modal = $modal;
       
    }


    public function index()
    {
        $data =  SettingService::appInformations( $this->modal->pluck('value', 'key'));

        $gateways = SMS::latest()->get();

        return view('dashboard.settings' , compact('data' , 'gateways'));
    }

    public function update(Request $request)
    {
        foreach ( $request->all() as $key => $val ){
            if (in_array($key , ['logo' , 'fav_icon','intro_loader_en','logo_en' , 'default_user' , 'intro_loader' ,'be_our_customer_image', 'intro_logo'  ,'about_image_2' , 'about_image_1' , 'login_background'])) {
                $name = $val->store('files');

                    $Setting = Setting::where('key',$key)->count();

                    if($Setting)
                    {
                        $this->modal->where( 'key', $key ) -> update( [ 'value' => $name ] );
                    }else{
                        Setting::create([
                            'type' => 'image',
                            'key' => $key,
                            'value' => $name
                        ]);
                    }
            }else if($val){
                $checkSetting = Setting::where('key',$key)->count();
                if($checkSetting)
                {
                    $this->modal->where( 'key', $key )->update(['value' => $val]);
                }else{
                    Setting::create([
                        'type' => 'text',
                        'key' => $key,
                        'value' => $val
                    ]);
                }
            }
        }
        if ($request->is_production) {
            $this->modal->where( 'key', 'is_production' ) -> update( [ 'value' => 1 ] );
        }else{
            $this->modal->where( 'key', 'is_production' ) -> update( [ 'value' => 0 ] );
        }

        return SettingService::appInformations($this->modal->pluck('value', 'key'));
    
            
    }


    public function updateSms(Request $request)
    {
        SMS::where('id' , $request->sms_id)->update($request->except('_token' , 'sms_id'));
        return response()->json();
    }



    public function SetLanguage($lang)
    {
    
        if ( in_array( $lang, [ 'ar', 'en' ,'ps'] ) ) {

            if ( session() -> has( 'lang' ) )
                session() -> forget( 'lang' );

            session() -> put( 'lang', $lang );

        } else {
            if ( session() -> has( 'lang' ) )
                session() -> forget( 'lang' );

            session() -> put( 'lang', 'ar' );
        }
        return back();
    }

}