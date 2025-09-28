<?php

namespace App\Http\Middleware;

use App\Traits\AdminFirstRouteTrait;
use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use App\Models\Custody;
use App\Models\Page;

class CheckRoleMiddleware
{
    use ApiResponseTrait, AdminFirstRouteTrait;

    public function handle($request, Closure $next)
    {
        // Super Admin bypass - User ID 1 only has all permissions
        if ((isset(auth()->user()->id) && auth()->user()->id == 1) || ($page = Page::where('url', $request->url())->first() ? !$page->is_authenticated : false)) {
            return $next($request);
        }
        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect(route('show.login'));
        }
        //    dd(auth()->user()->roles()->first());

        // Check if user has any roles
        $userRole = auth()->user()->roles()->first();
        if (!$userRole) {
            // User has no roles, set empty permissions array
            $permissions = collect(['home', 'logout']);
        } else {
            // User has roles, get permissions
            $permissions = $userRole->permissions()->pluck('name');
            $permissions->push('home');
            $permissions->push('logout');
        }
        $permissions = $permissions->toArray();
        // some exception
        $excpetions = ['edit-profile', 'home', 'logout', 'payments.print', 'money-transfer', 'orders.quote', 'orders.invoice', 'warehouse_sales.show', 'warehouse_sales.store', 'warehouse_sales.destroy', 'warehouse_sales.update', 'order.verified', 'payment-links.*', 'payment-links.create', 'payment-links.index', 'stock.decrement', "orders.registeration-forms", 'orders.registeration-forms.edit', 'orders.registeration-forms.destroy', 'orders.registeration-forms.fetch', 'orders.registeration-forms.search', 'orders.registeration-forms.update', 'orders.customers.check'];
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
            $currunt_route = str_replace('bookingsStore.addons', 'bookings.addons', $currunt_route);
            $currunt_route = str_replace('ordersStore.addons', 'orders.addons', $currunt_route);
            $currunt_route = str_replace('orders.removeAddon', 'orders.addons', $currunt_route);
            $currunt_route = str_replace('bookings.removeAddon', 'bookings.addons', $currunt_route);
            $currunt_route = str_replace('ordersUpdate.addons', 'orders.addons', $currunt_route);
            $currunt_route = str_replace('bookingsUpdate.addons', 'bookings.addons', $currunt_route);
            $currunt_route = str_replace('orders.updateInsurance', 'orders.insurance', $currunt_route);
            $currunt_route = str_replace('orders.updatesignin', 'orders.signin', $currunt_route);
            $currunt_route = str_replace('orders.uploadTemporaryImage', 'orders.signin', $currunt_route);
            $currunt_route = str_replace('orders.removeImage', 'orders.signin', $currunt_route);
            $currunt_route = str_replace('payments.verified', 'payments.show', $currunt_route);
            $currunt_route = str_replace('payments.verified', 'payments.index', $currunt_route);

            $currunt_route = str_replace('warehouse_sales.show', 'warehouse_sales.show', $currunt_route);
            $currunt_route = str_replace('warehouse_sales.store', 'warehouse_sales.store', $currunt_route);
            $currunt_route = str_replace('warehouse_sales.destroy', 'warehouse_sales.destroy', $currunt_route);


            if ($actAs) {
                $currunt_route = str_replace($currunt_route, $actAs, $currunt_route);
            }
        }
        if (!str_contains($currunt_route, 'settings') and !\str_contains($currunt_route, 'sms') and !in_array($currunt_route, $excpetions)) {
            $currunt_route = str_replace('update', 'edit', $currunt_route);
        }

        // Route to Permission mapping - for cases where route name differs from permission name
        $routePermissionMapping = [
            'orders.index' => 'bookings.index',
            'orders.create' => 'bookings.create',
            'orders.show' => 'bookings.view',
            'orders.edit' => 'bookings.edit',
            'orders.destroy' => 'bookings.delete',
            // Add more mappings here if needed
        ];

        // Special handling for services routes - allow camp-types permissions
        if (str_starts_with($currunt_route, 'services.')) {
            $campTypePermission = str_replace('services.', 'camp-types.', $currunt_route);
            if (in_array($campTypePermission, $permissions)) {
                // User has camp-types permission, allow access
                \View::share('roles', $permissions);
                return $next($request);
            }
        }

        // Special handling for calender route - allow scheduling permissions
        if ($currunt_route === 'calender') {
            if (in_array('scheduling.index', $permissions)) {
                // User has scheduling permission, allow access
                \View::share('roles', $permissions);
                return $next($request);
            }
        }

        // Special handling for equipment-directories routes - allow equipment permissions
        if (str_starts_with($currunt_route, 'equipment-directories.')) {
            $equipmentPermission = str_replace('equipment-directories.', 'equipment.', $currunt_route);
            if (in_array($equipmentPermission, $permissions)) {
                // User has equipment permission, allow access
                \View::share('roles', $permissions);
                return $next($request);
            }
        }

        // Check if current route has a permission mapping
        $permissionToCheck = $routePermissionMapping[$currunt_route] ?? $currunt_route;



        if (!in_array($permissionToCheck, $permissions) and !in_array($currunt_route, $excpetions) and !$isWildcardException) {

            $msg = trans('auth.not_authorized');
            if ($request->ajax()) {
                return $this->unauthorizedReturn(['type' => 'notAuth']);
            }

            if (count($permissions) <= 2) {
                // User has no permissions except home and logout
                session()->flash('danger', 'You have no permissions assigned. Please contact administrator.');
                return redirect()->route('home');
            }

            session()->flash('danger', $msg);

            return redirect()->route($this->getAdminFirstRouteName($permissions));
        }



        \View::share('roles', $permissions);

        return $next($request);
    }
}
