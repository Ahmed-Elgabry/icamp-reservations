<?php

namespace App\Traits;
use App\Models\Setting;

trait  Firebase
{
    use NotificationMessageTrait ;
    
    public function sendFcmNotification($tokens = [], $types = [] , $data = [] , $lang = 'ar')
    {
        $SERVER_API_KEY = Setting::where('key' , 'firebase_key')->first()->value ;
        $Notify_data = [];
        foreach($types as $device_type){
            if($device_type == 'ios'){
                $Notify_data = [
                    "registration_ids" => $tokens,
                    "notification" => [
                        "title"    => $this->getTitle($data['type'] ,  $lang  ),
                        "body"     => $this->getBody($data ,  $lang  ) ,
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
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
    }
}

