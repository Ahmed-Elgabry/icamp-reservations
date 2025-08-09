<?php

namespace App\Notifications;

use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Traits\Firebase;

class GiftNotification extends Notification
{
    use Queueable , Firebase;

    protected $tokens, $data , $type , $platforms; 

    public function __construct( $data , $tokens , $type ,$platforms)
    {
        $this->tokens = $tokens;
        $this->data   = $data;
        $this->type   = $type;
        $this->platforms   = $platforms;
    }

    
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {

    
      return NotificationService::GiftNotification($this->tokens, $this->data ,$this->platforms ,$this->type);
       
    }
}
