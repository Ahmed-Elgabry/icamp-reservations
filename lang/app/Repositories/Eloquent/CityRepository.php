<?php

namespace App\Repositories\Eloquent;

use App\Models\City;
use App\Repositories\ICityRepository;

class CityRepository extends BaseRepository implements ICityRepository
{
    public function __construct()
    {
        $this->model = new City();
    }
    
}