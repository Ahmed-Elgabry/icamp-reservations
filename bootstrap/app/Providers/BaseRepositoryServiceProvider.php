<?php

namespace App\Providers;

use App\Repositories\Eloquent\BaseRepository;

use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Eloquent\AddressRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\IAddressRepository;
use App\Repositories\IOrderRepository;
use App\Repositories\IPermissionRepository;
use App\Repositories\IRoleRepository;
use Illuminate\Support\ServiceProvider;

class BaseRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(IRoleRepository::class , RoleRepository::class);
        $this->app->bind(IPermissionRepository::class , PermissionRepository::class);
       

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}