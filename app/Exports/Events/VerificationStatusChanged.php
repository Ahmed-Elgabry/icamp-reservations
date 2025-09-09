<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VerificationStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action; // 'addon' | 'payment' | 'expense' | 'warehouse_sale'
    public $item; // The related model instance (polymorphic)
    public bool $verified; // New verification state

    public function __construct(string $action, $item, bool $verified)
    {
        $this->action = $action;
        $this->item = $item;
        $this->verified = $verified;
    }
}
