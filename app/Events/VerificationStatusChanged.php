<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VerificationStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action; 
    public $item; 
    public string $verified; // current verification state

    public function __construct(string $action, $item, string $verified)
    {
        $this->action = $action;
        $this->item = $item;
        $this->verified = $verified;
    }
}
