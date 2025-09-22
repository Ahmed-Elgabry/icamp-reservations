<?php

namespace App\Repositories\Eloquent;

use App\Models\Package;
use App\Repositories\IPackageRepository;

class PackageRepository extends BaseRepository implements IPackageRepository
{
    public function __construct()
    {
        $this->model = new Package();
    }
    
}