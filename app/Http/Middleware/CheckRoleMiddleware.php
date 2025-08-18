<?php

namespace App\Http\Middleware;

use App\Traits\AdminFirstRouteTrait;
use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use App\Models\Custody;

class CheckRoleMiddleware
{
  use ApiResponseTrait, AdminFirstRouteTrait;

  public function handle($request, Closure $next)
  {


    if (!auth()->user()->is_active) {
      auth()->logout();
      return redirect(route('show.login'));
    }
    //    dd(auth()->user()->roles()->first());

    $permissions = auth()->user()->roles()->first()->permissions()->pluck('name');
    $permissions->push('home');
    $permissions->push('logout');
    $permissions = $permissions->toArray();
    // some exception
    $excpetions = ['edit-profile', 'home', 'logout', 'payments.print', 'orders.quote', 'orders.invoice', 'addons.print', 'warehouse_sales.show', 'warehouse_sales.store', 'warehouse_sales.destroy', 'warehouse_sales.update', 'stocks.destroyServiceStock', 'order.verified', 'payment-links.*'];

    $currunt_route = Route::currentRouteName();
    $route = Route::current();
    $actAs = $route->action['act-as'] ?? null;

    // Check if current route matches any wildcard exceptions
    $isWildcardException = false;
    foreach ($excpetions as $exception) {
      if (str_ends_with($exception, '.*')) {
        $prefix = str_replace('.*', '', $exception);
        if (str_starts_with($currunt_route, $prefix . '.')) {
          $isWildcardException = true;
          break;
        }
      }
    }

    if (!in_array($currunt_route, $excpetions) && !$isWildcardException) {
      $currunt_route = str_replace('update-settings', 'settings', $currunt_route);
      $currunt_route = str_replace('store', 'create', $currunt_route);
      $currunt_route = str_replace('adminsfile.destroy', 'admins.destroy', $currunt_route);
      $currunt_route = str_replace('update.reports', 'orders.reports', $currunt_route);
      $currunt_route = str_replace('ordersStore.addons', 'orders.addons', $currunt_route);
      $currunt_route = str_replace('orders.removeAddon', 'orders.addons', $currunt_route);
      $currunt_route = str_replace('ordersUpdate.addons', 'orders.addons', $currunt_route);
      $currunt_route = str_replace('orders.updateInsurance', 'orders.insurance', $currunt_route);
      $currunt_route = str_replace('orders.updatesignin', 'orders.signin', $currunt_route);
      $currunt_route = str_replace('orders.uploadTemporaryImage', 'orders.signin', $currunt_route);
      $currunt_route = str_replace('orders.removeImage', 'orders.signin', $currunt_route);
      $currunt_route = str_replace('payments.verified', 'payments.show', $currunt_route);
      $currunt_route = str_replace('payments.verified', 'payments.index', $currunt_route);
      $currunt_route = str_replace('addons.print', 'addons.print', $currunt_route);
      $currunt_route = str_replace('warehouse_sales.show', 'warehouse_sales.show', $currunt_route);
      $currunt_route = str_replace('warehouse_sales.store', 'warehouse_sales.store', $currunt_route);
      $currunt_route = str_replace('warehouse_sales.destroy', 'warehouse_sales.destroy', $currunt_route);
      $currunt_route = str_replace('stocks.destroyServiceStock', 'stocks.destroyServiceStock', $currunt_route);

      if ($actAs) {
        $currunt_route = str_replace($currunt_route, $actAs, $currunt_route);
      }
    }



    if (!str_contains($currunt_route, 'settings') and !\str_contains($currunt_route, 'sms') and !in_array($currunt_route, $excpetions)) {
      $currunt_route = str_replace('update', 'edit', $currunt_route);
    }

    if (!in_array($currunt_route, $permissions) and !in_array($currunt_route, $excpetions) and !$isWildcardException) {

      $msg = trans('auth.not_authorized');
      if ($request->ajax()) {
        return $this->unauthorizedReturn(['type' => 'notAuth']);
      }

      if (!count($permissions)) {
        session()->invalidate();
        session()->regenerateToken();
        return redirect(route('show.login'));
      }

      session()->flash('danger', $msg);

      return redirect()->route($this->getAdminFirstRouteName($permissions));
    }



    \View::share('roles', $permissions);

    return $next($request);
  }
}
