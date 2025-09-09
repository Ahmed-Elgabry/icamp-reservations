<?php

namespace App\Repositories\Eloquent;

use App\Repositories\IRoleRepository;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository implements IRoleRepository
{
    public function __construct()
    {
        $this->model = new Role();
    }
    
}