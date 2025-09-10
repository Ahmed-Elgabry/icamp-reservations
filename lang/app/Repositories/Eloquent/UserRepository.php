<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\IUserRepository;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function __construct()
    {
        $this->model = new User();
    }

    public function AllUsers()
    {
       return $this->model->users()->latest()->get();
    }
    
}