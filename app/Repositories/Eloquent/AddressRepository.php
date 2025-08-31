<?php

namespace App\Repositories\Eloquent;

use App\Models\Address;
use App\Repositories\IAddressRepository;

class AddressRepository extends BaseRepository implements IAddressRepository
{
    public function __construct()
    {
        $this->model = new Address();
    }
    
}