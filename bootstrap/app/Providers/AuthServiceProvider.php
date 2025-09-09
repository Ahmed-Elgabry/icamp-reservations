<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\AdminPolicy',
        'App\Models\Customer' => 'App\Policies\CustomerPolicy',
        'App\Models\Order' => 'App\Policies\OrderPolicy',
        'App\Models\Task' => 'App\Policies\TaskPolicy',
        'App\Models\Stock' => 'App\Policies\StockPolicy',
        'App\Models\Addon' => 'App\Policies\AddonPolicy',
        'App\Models\Payment' => 'App\Policies\PaymentPolicy',
        'App\Models\Expense' => 'App\Policies\ExpensePolicy',
        'App\Models\Meeting' => 'App\Policies\MeetingPolicy',
        'App\Models\Violation' => 'App\Policies\ViolationPolicy',
        'App\Models\DailyReport' => 'App\Policies\DailyReportPolicy',
        'App\Models\ExpenseItem' => 'App\Policies\ExpenseItemPolicy',
        'App\Models\BankAccount' => 'App\Policies\BankAccountPolicy',
        'App\Models\EquipmentDirectory' => 'App\Policies\EquipmentPolicy',
        'App\Models\Survey' => 'App\Policies\SurveyPolicy',
        'App\Models\Service' => 'App\Policies\ServicePolicy',
        'App\Models\CampReport' => 'App\Policies\CampReportPolicy',
        'App\Models\TermsSittng' => 'App\Policies\TermsSettingPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Super Admin bypass for all Gates/Permissions
        Gate::before(function ($user, $ability) {
            if ($user && $user->id == 1) {
                return true; // Super Admin bypasses all permission checks
            }

            return null; // Continue with normal permission checks
        });

        // Define Gates for all permissions dynamically
        Gate::define('customers.index', function ($user) {
            return $user->hasPermissionTo('customers.index');
        });

        Gate::define('customers.create', function ($user) {
            return $user->hasPermissionTo('customers.create');
        });

        Gate::define('customers.edit', function ($user) {
            return $user->hasPermissionTo('customers.edit');
        });

        Gate::define('customers.destroy', function ($user) {
            return $user->hasPermissionTo('customers.destroy');
        });

        Gate::define('customers.deleteAll', function ($user) {
            return $user->hasPermissionTo('customers.deleteAll');
        });

        // Define Gates for bookings permissions
        Gate::define('bookings.index', function ($user) {
            return $user->hasPermissionTo('bookings.index');
        });

        Gate::define('bookings.create', function ($user) {
            return $user->hasPermissionTo('bookings.create');
        });


        Gate::define('bookings.edit', function ($user) {
            return $user->hasPermissionTo('bookings.edit');
        });

        Gate::define('bookings.destroy', function ($user) {
            return $user->hasPermissionTo('bookings.delete');
        });

        // Define Gates for services permissions with camp-types fallback
        Gate::define('services.index', function ($user) {
            return $user->hasPermissionTo('services.index') || $user->hasPermissionTo('camp-types.index');
        });

        Gate::define('services.create', function ($user) {
            return $user->hasPermissionTo('services.create') || $user->hasPermissionTo('camp-types.create');
        });

        Gate::define('services.edit', function ($user) {
            return $user->hasPermissionTo('services.edit') || $user->hasPermissionTo('camp-types.edit');
        });

        Gate::define('services.destroy', function ($user) {
            return $user->hasPermissionTo('services.destroy') || $user->hasPermissionTo('camp-types.delete');
        });

        // Define Gates for camp-types permissions
        Gate::define('camp-types.index', function ($user) {
            return $user->hasPermissionTo('camp-types.index');
        });

        Gate::define('camp-types.create', function ($user) {
            return $user->hasPermissionTo('camp-types.create');
        });

        Gate::define('camp-types.edit', function ($user) {
            return $user->hasPermissionTo('camp-types.edit');
        });

        Gate::define('camp-types.destroy', function ($user) {
            return $user->hasPermissionTo('camp-types.delete');
        });

        // Define Gates for scheduling permissions
        Gate::define('scheduling.index', function ($user) {
            return $user->hasPermissionTo('scheduling.index');
        });

        Gate::define('scheduling.create', function ($user) {
            return $user->hasPermissionTo('scheduling.create');
        });

        Gate::define('scheduling.edit', function ($user) {
            return $user->hasPermissionTo('scheduling.edit');
        });

        Gate::define('scheduling.delete', function ($user) {
            return $user->hasPermissionTo('scheduling.delete');
        });

        // Equipment Directories permissions
        Gate::define('equipment-directories.index', function ($user) {
            return $user->hasPermissionTo('equipment-directories.index');
        });
        Gate::define('equipment-directories.create', function ($user) {
            return $user->hasPermissionTo('equipment-directories.create');
        });
        Gate::define('equipment-directories.edit', function ($user) {
            return $user->hasPermissionTo('equipment-directories.edit');
        });
        Gate::define('equipment-directories.destroy', function ($user) {
            return $user->hasPermissionTo('equipment-directories.destroy');
        });

        // Equipment Directory Items permissions
        Gate::define('equipment-directories.items.index', function ($user) {
            return $user->hasPermissionTo('equipment-directories.items.index');
        });
        Gate::define('equipment-directories.items.create', function ($user) {
            return $user->hasPermissionTo('equipment-directories.items.create');
        });
        Gate::define('equipment-directories.items.edit', function ($user) {
            return $user->hasPermissionTo('equipment-directories.items.edit');
        });
        Gate::define('equipment-directories.items.destroy', function ($user) {
            return $user->hasPermissionTo('equipment-directories.items.destroy');
        });

        // Equipment permissions (for equipment.* permissions)
        Gate::define('equipment.index', function ($user) {
            return $user->hasPermissionTo('equipment.index');
        });
        Gate::define('equipment.create', function ($user) {
            return $user->hasPermissionTo('equipment.create');
        });
        Gate::define('equipment.edit', function ($user) {
            return $user->hasPermissionTo('equipment.edit');
        });
        Gate::define('equipment.destroy', function ($user) {
            return $user->hasPermissionTo('equipment.destroy');
        });
    }
}
