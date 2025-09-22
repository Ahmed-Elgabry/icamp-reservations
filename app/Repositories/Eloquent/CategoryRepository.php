<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\ICategoryRepository;

class CategoryRepository extends BaseRepository implements ICategoryRepository
{
    public function __construct()
    {
        $this->model = new Category();
    }
    
}