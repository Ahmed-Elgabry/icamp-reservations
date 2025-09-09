<?php

namespace App\Repositories\Eloquent;

use App\Models\InitialPage;
use App\Repositories\IInitialPageRepository ;

class InitialPageRepository extends BaseRepository implements IInitialPageRepository 
{
    public function __construct()
    {
        $this->model = new InitialPage();
    }
    
}