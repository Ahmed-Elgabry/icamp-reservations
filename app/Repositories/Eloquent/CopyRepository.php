<?php

namespace App\Repositories\Eloquent;

use App\Models\Copy;
use App\Repositories\ICopyRepository;
use App\Repositories\Eloquent\BaseRepository;

class CopyRepository extends BaseRepository implements ICopyRepository
{
    public function __construct()
    {
        $this->model = new Copy();
    }
    
}