<?php
namespace App\Repositories\Eloquent;

use App\Repositories\IPermissionRepository;
use Spatie\Permission\Models\Permission;


class PermissionRepository extends BaseRepository implements IPermissionRepository
{
    public function __construct()
    {
        $this->model = new Permission();
    }

}