<?php

namespace App\Notifications;

use App\Services\NotificationService;
use App\Traits\Firebase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TargetNotification extends Notification
{
    use Queueable , Firebase;

    protected $tokens, $data , $type , $platforms; 

    public function __construct( $data , $tokens , $type ,$platforms)
    {
        $this->tokens      = $tokens;
        $this->data        = $data;
        $this->type        = $type;
        $this->platforms   = $platforms;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {


      return NotificationService::TargetNotification($this->tokens, $this->data ,$this->platforms ,$this->type);
        
    }
}
