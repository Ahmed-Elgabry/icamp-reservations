<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\IOrderRepository;

class OrderRepository extends BaseRepository implements IOrderRepository
{
    public function __construct()
    {
        $this->model = new Order();
    }
    
}