<?php

namespace App\Repositories\Eloquent;

use App\Models\Banner;
use App\Repositories\IBannerRepository;

class BannerRepository extends BaseRepository implements IBannerRepository
{
    public function __construct()
    {
        $this->model = new Banner();
    }
    
}