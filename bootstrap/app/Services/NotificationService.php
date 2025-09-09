<?php

namespace App\Services;

use App\Http\Resources\Api\OrderResource;
use App\Http\Resources\Api\UserResource;
use App\Http\Resources\Api\OfferResource;
use App\Http\Resources\Api\TargetResource;
use App\Http\Resources\Api\WalletTransactionResource;

class NotificationService
{

    public static function setAdminNotification($tokens,$data = [] , $platforms = [])
    {
        $result  =  [
            'title_ar'     => $data['title_ar'] ?? 'هوزن تك',
            'title_en'     => $data['title_en'] ?? 'HWZN',
            'body_ar'      => $data['body_ar']  ?? '',
            'body_en'      => $data['body_en']  ?? '',
            'type'         => isset($data['type']) ? $data['type'] : 'admin',
            'order_id'     => null,
            'status'       => null,
        ];
        pushNotification($tokens, $result , $platforms);
        return $result;
    }

    public static function setAppNotification($tokens = [],$data = [] , $platforms = [])
    {
        $result =  [
            'title_ar'    => $data['title_ar'] ?? 'هوزن تك',
            'title_en'    => $data['title_en'] ?? 'HWZN',
            'body_ar'     => $data['body_ar']  ?? '',
            'body_en'     => $data['body_en']  ?? '',
            'type'        => 'admin',
            'order_id'    => null,
            'status'      => null,
        ];
        pushNotification($tokens,$result , $platforms);
        return $result;
    }


    public static function setOrderFcm($data)
    {
        $body             = 'هوزن تك';
        $title            = 'HWZN';
        if($data->status== 0)
        {
            $title        =  __('apis.new_order_title') . $data->id;
            $body         =  __('apis.new_order_body');
        }
        return [
            'title'       => $title,
            'body'        => $body,
        ];
    }

    public static function setFcm($data = [])
    {
        return [
            'title'       => 'HWZN',
            'body'        => $data['body'] ?? '',
        ];
    }


    public static function setRateNotification($tokens , $data , $platforms = [])
    {

        $result =  [
            'title_ar'    => 'طلب رقم #  '  . $data->id  ,
            'title_en'    => 'Order Number # ' . $data->id  ,
            'body_ar'     => 'لديك تقييم جديد' ,
            'body_en'     => 'You have new rate',
            'type'        => 'rate',
            'id'          => $data->id,
            'status'      => $data->status,
            'data'        => new OrderResource($data)
        ];
        pushNotification($tokens,$result , $platforms);
        return $result;
    }

    public static function AssignOrderNotification($tokens , $data  , $platforms = [] , $type)
    {

        $result =  [
            'title_ar'    => 'طلب رقم #  '  . $data->order_num  ,
            'title_en'    => 'Order Number # ' . $data->order_num ,
            'body_ar'     => ' لديك طلب جديد' ,
            'body_en'     => 'You have new order',
            'type'        => $type,
            'id'          => $data->id,
            'status'      => $data->status,
            'data'        => new OrderResource($data)
        ];
        pushNotification($tokens, $result , $platforms);
        return $result;
    }

   
    public static function GiftNotification($tokens , $data  , $platforms = [] , $type)
    {

        $result =  [
            'title_ar'    => 'هدية جديدة'  ,
            'title_en'    => 'New Gift' ,
            'body_ar'     => 'تهانينا لقد  حصلت علي هدية جديدة' ,
            'body_en'     => 'Congratulations ! You have recieved new gift',
            'type'        => $type,
            'id'          => $data->id,
            'status'      => null,
            'data'        => new WalletTransactionResource($data)
        ];
        pushNotification($tokens, $result , $platforms);
        return $result;
    }
    
    public static function setOrderNotification($tokens , $data  , $platforms = [] , $type)
    {

        $result =  [
            'title_ar'    => 'طلب رقم #  '  . $data->order_num ,
            'title_en'    => 'Order Number # ' . $data->order_num ,
            'body_ar'     => ' لديك طلب جديد' ,
            'body_en'     => 'You have new order',
            'type'        => $type,
            'id'          => $data->id,
            'status'      => $data->status,
            'data'        => new OrderResource($data)
        ];
        pushNotification($tokens,$result , $platforms );
        return $result;
    }

    public static function setOfferNotification($tokens , $data  , $platforms = [])
    {

        $result =  [
            'title_ar'    => 'طلب رقم #  '  . $data->order->order_num ,
            'title_en'    => 'Order Number # ' . $data->order->order_num  ,
            'body_ar'     => ' لديك عرض جديد' ,
            'body_en'     => 'You have new offer',
            'type'        => 'offer',
            'id'          => $data->id,
            'status'      => $data->order->status,
            'data'        => new OfferResource($data)
        ];
        pushNotification($tokens,$result  , $platforms);
        return $result;
    }

    public static function setCancelOrderNotification($tokens , $data  , $platforms = [])
    {

        $result =  [
            'title_ar'    => 'طلب رقم #  '  . $data->order_num  ,
            'title_en'    => 'Order Number # ' . $data->order_num  ,
            'body_ar'     => 'لقد تم الغاء الطلب' ,
            'body_en'     => 'Order cancelled',
            'type'        => 'order',
            'id'          => $data->id,
            'status'      => $data->status,
            'data'        => new OrderResource($data)
        ];
        pushNotification($tokens,$result  , $platforms);
        return $result;
    }

    public static function setOrderStatusNotification($tokens , $data  , $platforms = [])
    {

        $result =  [
            'title_ar'    => 'طلب رقم #  '  . $data->order_num  ,
            'title_en'    => 'Order Number # ' . $data->order_num  ,
            'body_ar'     => 'لقد تم تغيير حالة الطلب' ,
            'body_en'     => 'Order Status changed by provider',
            'type'        => 'order',
            'id'          => $data->id,
            'status'      => $data->status,
            'data'        => new OrderResource($data)
        ];
        pushNotification($tokens,$result , $platforms);
        return $result;
    }

    public static function setAcceptOrderNotification($tokens , $data  , $platforms = [])
    {

        $result =  [
            'title_ar'    => 'طلب رقم #  '  . $data->order_num ,
            'title_en'    => 'Order Number # ' . $data->order_num ,
            'body_ar'     => 'لقد تم قبول الطلب' ,
            'body_en'     => 'Order accepted by provider',
            'type'        => 'order',
            'id'          => $data->id,
            'status'      => $data->status,
            'data'        => new OrderResource($data)
        ];
        pushNotification($tokens,$result , $platforms);
        return $result;
    }

    public static function setApprovedNotification($tokens , $data  , $platforms = [])
    {

        $result =  [
            'title_ar'    => 'موافقة الادارة' ,
            'title_en'    => 'Admin Approve',
            'body_ar'     => 'لقد تم قبولكم كمزود خدمة   ' ,
            'body_en'     => 'You Have approved by admin as provider',
            'type'        => 'approve',
            'id'          => $data->id,
            'status'      => $data->status,
            'data'        => new UserResource($data)
        ];
        pushNotification($tokens,$result , $platforms);
        return $result;
    }



}