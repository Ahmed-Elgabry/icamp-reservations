<?php

namespace App\Repositories\Eloquent;

use App\Models\Coupon;
use App\Repositories\ICouponRepository;

class CouponRepository extends BaseRepository implements ICouponRepository
{
    public function __construct()
    {
        $this->model = new Coupon();
    }
    
}