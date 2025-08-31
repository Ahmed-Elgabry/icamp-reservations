<?php
    /*
    |--------------------------------------------------------------------------
    | Detect Active URL Segment Function
    |--------------------------------------------------------------------------
    |
    | Compare given url segment with current url and return output if they match.
    | Very useful for navigation, marking if the link is active.
    |
    */

use App\Models\Setting;


function campInventory()
{
    return $campInventory = [
        'حمام كرفان (Caravan toilet)' => 1,
        'معطر اليدين (Hand freshener)' => 8,
        'شمعة + كريم لوشن (Candle + lotion)' => 1,
        'مزهرية ورد حمام + مناديل (Flower tissue + vase)' => 1,
        'معطر الجو (Toilet air freshener)' => 9,
        'حفرة البالوعة (Drain hole)' => 1,
        'كيس قمامة + بروش حمام (Garbage bag + bathroom brooch)' => 11,
        'محلول البالوعة (B.T Chemical)' => 1,
        'صابون غسيل اليد (Hand washing soap)' => 7,
        'تعبئة خزان الماء (Filling water tank)' => 1,
        'معطر المرحاض (Toilet freshener)' => 10,
        'البرونزي (Bronze)' => 12,
        'جنريتر (Generator)' => 13,
        'دبة بترول (Petrol tank)' => 14,
        'إضاءة صفراء (Yellow lighting)' => 16,
        'رواق (Covered porch)' => 15,
        'كشاف أبيض (White flashlight)' => 17,
        'توصيلات (Connections)' => 18,
        'ستاند المخيم (Camp stand)' => 19,
        'إضاءة ستراب (Strap lighting)' => 20,
        'إضاءة زينة 1 (Decorative lighting 1)' => 21,
        'إضاءة زينة 2 (Decorative lighting 2)' => 22,
        'عشب صناعي (Artificial grass)' => 23,
        'جلسة أرضية (Floor sitting)' => 25,
        'سجاد (Carpets)' => 24,
        'طقم جلسة (Sitting set)' => 26,
        'طاولة خشب كبير (Large wooden table)' => 27,
        'طاولة خشب صغيرة (Small wooden table)' => 28,
        'مزهرية ورد كبير (Large rose vase)' => 29,
        'مناديل (Tissues)' => 30
    ];
}

function paymentMethod($select = null)
{
    $rows =  [
        'cash',
        'visa',
        'payment_link'
    ];

    if($select)
    {
        foreach($rows as $row)
        {
            if($row == $select)
            {
                return $row;
            }
        }
        return null;
    }

    return $rows;
}

function statements($select = null)
{
    $rows =  [
        'deposit',
        'complete the amount',
        'the_insurance',
    ];

    if($select)
    {
        foreach($rows as $row)
        {
            if($row == $select)
            {
                return $row;
            }
        }
        return null;
    }

    return $rows;
}

function isActiveURLSegment($pageSlug, $segment, $output = "active"){
    if (Request::segment($segment) == $pageSlug) return $output;
}



function isActivePanelSegment($pageSlug, $segment, $output = "show"){
    if (Request::segment($segment) == $pageSlug) return $output;
}


function settings($key) : string|null
{
    $setting = Setting::where('key' , $key)->first();
    if($setting){
        return $setting->value ;
    }else{
        return null ;
    }
}

// invoices
// settings

/*
|--------------------------------------------------------------------------
| Detect Active Route Function
|--------------------------------------------------------------------------
|
| Compare given route with current route and return output if they match.
| Very useful for navigation, marking if the link is active.
|
*/
function isActiveRoute($route, $output = "active"){
    if (Route::currentRouteName() == $route) return $output;
}

/*
|--------------------------------------------------------------------------
| Detect Active Routes Function
|--------------------------------------------------------------------------
|
| Compare given routes with current route and return output if they match.
| Very useful for navigation, marking if the link is active.
|
*/
function areActiveRoutes(Array $routes, $output = "active show"){
    foreach ($routes as $route){
        if (Route::currentRouteName() == $route) return $output;
    }
}

/*
|--------------------------------------------------------------------------
| Format SEO Page Slug Function
|--------------------------------------------------------------------------
|
| Used to format the SEO Page Slug and return the formatted slug for
| translations.
|
*/
function formatSEOPageSlug($pageSlug){
    return __('general.seo_'.str_replace("-", "_", $pageSlug).'_title');
}


function lang(){
    return App() -> getLocale();
}

function generateRandomCode(){
    return '1234';
    return rand(1111,4444);
}

if (!function_exists('languages')) {
    function languages() {
    return ['ar', 'en','ps'];
    }
}

if (!function_exists('defaultLang')) {
    function defaultLang() {
    return 'ar';
    }
}



function pushNotification($tokens , $data , $platforms)
{

    $url = 'https://fcm.googleapis.com/fcm/send';
    $SERVER_API_KEY = Setting::where('key' , 'firebase_key')->first()->value ;

    // $SERVER_API_KEY = 'AAAAi0Y_HnY:APA91bGeuHqUXsXiwWMDlJ-tenEOiKmRZ7pfifFPvI0XUzUiIRD6togg468docAR0gdTpY40Yvr50I8610Fdm9jG3RT-iYakNLthfVcxViBSJ6lIzt5gVh77Y_4VY3oqYyP64Svx6QxR';

    // $data = [
    //     "registration_ids" => $tokens,
    //     "notification" => [
    //         'title'                  => $data['title_'.lang()],
    //         'body'                   => $data['body_'.lang()] ,
    //         'data'                   => json_encode(array('type' => $data['type'] ,'order_id' => $data['order_id']??null ,'provider_id' => $data['provider_id'] ??null,'delegate_id' => $data['delegate_id']??null,'status' => $data['status']??null)),
    //         'sound'                  => 'default',
    //     ]
    // ];

    foreach($platforms as $device_type){
        if($device_type == 'ios'){
            $Notify_data = [
                "registration_ids" => $tokens,
                "notification" => [
                    "title"    => $data['title_'.lang()],
                    "body"     =>  $data['body_'.lang()]  ,
                    "mutable_content" => true,
                    'sound'    => true,
                ],
                'data'  => $data
            ];
        }else{
            $Notify_data = [
                "registration_ids" => $tokens,
                'data'  => $data
            ];
        }
    }
    $dataString = json_encode($Notify_data);

    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    return response()->json();

}



?>
