<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

trait AdminFirstRouteTrait {
  public function getAdminFirstRouteName($authRoutes = null) {
    $routeName = 'home';

    if (!$authRoutes) {
      $authRoutes = Permission::where('role_id', auth()->user()->hasRole(1))
        ->pluck('permission')
        ->toArray();
    }

    $routes = Route::getRoutes();

    foreach ($routes as $route) {
      if (isset($route->getAction()['icon'])
        && in_array($route->getName(), $authRoutes)) {

        if (!isset($route->getAction()['sub_route'])
          || false == $route->getAction()['sub_route']) {

          $routeName = $route->getName();
          break;
        }

      }
    }

    return $routeName;
  }
}