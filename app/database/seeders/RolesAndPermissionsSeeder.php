<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions

        $routes_data    = [];
        $seen_names = [];

        foreach (Route::getRoutes() as $route) {
            if ($route->getName()) {

                if (in_array($route->getName(), $seen_names)) {
                    continue;
                }
                $routes_data[]   = [
                    'name' => $route->getName(),
                    'nickname_en' =>  $route->getName(),
                    'nickname_ar' =>  $route->getName(),
                    'guard_name' => 'web'
                ];
            }
        }

        // Use updateOrCreate instead of insert to handle duplicates
        foreach ($routes_data as $permissionData) {
            Permission::updateOrCreate(
                ['name' => $permissionData['name'], 'guard_name' => $permissionData['guard_name']],
                $permissionData
            );
        }

        // Additional permissions for the system
        $additional_permissions = [
            // Customers
            ['name' => 'customers.index', 'nickname_ar' => 'عرض العملاء', 'nickname_en' => 'View Customers', 'group' => 'customers'],
            ['name' => 'customers.create', 'nickname_ar' => 'إضافة عميل', 'nickname_en' => 'Create Customer', 'group' => 'customers'],
            ['name' => 'customers.edit', 'nickname_ar' => 'تعديل عميل', 'nickname_en' => 'Edit Customer', 'group' => 'customers'],
            ['name' => 'customers.delete', 'nickname_ar' => 'حذف عميل', 'nickname_en' => 'Delete Customer', 'group' => 'customers'],
            ['name' => 'customers.view', 'nickname_ar' => 'عرض تفاصيل عميل', 'nickname_en' => 'View Customer Details', 'group' => 'customers'],

            // Strategies
            ['name' => 'strategies.index', 'nickname_ar' => 'عرض الاستراتيجيات', 'nickname_en' => 'View Strategies', 'group' => 'strategies'],
            ['name' => 'strategies.create', 'nickname_ar' => 'إضافة استراتيجية', 'nickname_en' => 'Create Strategy', 'group' => 'strategies'],
            ['name' => 'strategies.edit', 'nickname_ar' => 'تعديل استراتيجية', 'nickname_en' => 'Edit Strategy', 'group' => 'strategies'],
            ['name' => 'strategies.delete', 'nickname_ar' => 'حذف استراتيجية', 'nickname_en' => 'Delete Strategy', 'group' => 'strategies'],

            // Inventory
            ['name' => 'inventory.index', 'nickname_ar' => 'عرض المخزون', 'nickname_en' => 'View Inventory', 'group' => 'inventory'],
            ['name' => 'inventory.create', 'nickname_ar' => 'إضافة مخزون', 'nickname_en' => 'Create Inventory', 'group' => 'inventory'],
            ['name' => 'inventory.edit', 'nickname_ar' => 'تعديل مخزون', 'nickname_en' => 'Edit Inventory', 'group' => 'inventory'],
            ['name' => 'inventory.delete', 'nickname_ar' => 'حذف مخزون', 'nickname_en' => 'Delete Inventory', 'group' => 'inventory'],
            ['name' => 'inventory.reports', 'nickname_ar' => 'تقارير المخزون', 'nickname_en' => 'Inventory Reports', 'group' => 'inventory'],

            // Addons
            ['name' => 'addons.index', 'nickname_ar' => 'عرض الإضافات', 'nickname_en' => 'View Addons', 'group' => 'addons'],
            ['name' => 'addons.create', 'nickname_ar' => 'إضافة إضافة', 'nickname_en' => 'Create Addon', 'group' => 'addons'],
            ['name' => 'addons.edit', 'nickname_ar' => 'تعديل إضافة', 'nickname_en' => 'Edit Addon', 'group' => 'addons'],
            ['name' => 'addons.delete', 'nickname_ar' => 'حذف إضافة', 'nickname_en' => 'Delete Addon', 'group' => 'addons'],
            ['name' => 'addons.print', 'nickname_ar' => 'طباعة الإضافات', 'nickname_en' => 'Print Addons', 'group' => 'addons'],

            // Camp Types
            ['name' => 'camp-types.index', 'nickname_ar' => 'عرض أنواع المخيم', 'nickname_en' => 'View Camp Types', 'group' => 'camp_types'],
            ['name' => 'camp-types.create', 'nickname_ar' => 'إضافة نوع مخيم', 'nickname_en' => 'Create Camp Type', 'group' => 'camp_types'],
            ['name' => 'camp-types.edit', 'nickname_ar' => 'تعديل نوع مخيم', 'nickname_en' => 'Edit Camp Type', 'group' => 'camp_types'],
            ['name' => 'camp-types.delete', 'nickname_ar' => 'حذف نوع مخيم', 'nickname_en' => 'Delete Camp Type', 'group' => 'camp_types'],

            // Bookings
            ['name' => 'bookings.index', 'nickname_ar' => 'عرض الحجوزات', 'nickname_en' => 'View Bookings', 'group' => 'bookings'],
            ['name' => 'bookings.create', 'nickname_ar' => 'إضافة حجز', 'nickname_en' => 'Create Booking', 'group' => 'bookings'],
            ['name' => 'bookings.edit', 'nickname_ar' => 'تعديل حجز', 'nickname_en' => 'Edit Booking', 'group' => 'bookings'],
            ['name' => 'bookings.delete', 'nickname_ar' => 'حذف حجز', 'nickname_en' => 'Delete Booking', 'group' => 'bookings'],
            ['name' => 'bookings.view', 'nickname_ar' => 'عرض تفاصيل حجز', 'nickname_en' => 'View Booking Details', 'group' => 'bookings'],
            ['name' => 'bookings.approve', 'nickname_ar' => 'اعتماد حجز', 'nickname_en' => 'Approve Booking', 'group' => 'bookings'],
            ['name' => 'bookings.cancel', 'nickname_ar' => 'إلغاء حجز', 'nickname_en' => 'Cancel Booking', 'group' => 'bookings'],
            ['name' => 'bookings.reports', 'nickname_ar' => 'تقارير الحجوزات', 'nickname_en' => 'Booking Reports', 'group' => 'bookings'],

            // Financial - Accounts
            ['name' => 'financial.accounts.index', 'nickname_ar' => 'عرض الحسابات المالية', 'nickname_en' => 'View Financial Accounts', 'group' => 'financial'],
            ['name' => 'financial.accounts.create', 'nickname_ar' => 'إضافة حساب مالي', 'nickname_en' => 'Create Financial Account', 'group' => 'financial'],
            ['name' => 'financial.accounts.edit', 'nickname_ar' => 'تعديل حساب مالي', 'nickname_en' => 'Edit Financial Account', 'group' => 'financial'],
            ['name' => 'financial.accounts.delete', 'nickname_ar' => 'حذف حساب مالي', 'nickname_en' => 'Delete Financial Account', 'group' => 'financial'],

            // Financial - Transactions
            ['name' => 'financial.transactions.index', 'nickname_ar' => 'عرض المعاملات المالية', 'nickname_en' => 'View Financial Transactions', 'group' => 'financial'],
            ['name' => 'financial.transactions.create', 'nickname_ar' => 'إضافة معاملة مالية', 'nickname_en' => 'Create Financial Transaction', 'group' => 'financial'],
            ['name' => 'financial.transactions.edit', 'nickname_ar' => 'تعديل معاملة مالية', 'nickname_en' => 'Edit Financial Transaction', 'group' => 'financial'],
            ['name' => 'financial.transactions.delete', 'nickname_ar' => 'حذف معاملة مالية', 'nickname_en' => 'Delete Financial Transaction', 'group' => 'financial'],

            // Expense Items
            ['name' => 'expense-items.index', 'nickname_ar' => 'عرض بنود المصاريف', 'nickname_en' => 'View Expense Items', 'group' => 'expenses'],
            ['name' => 'expense-items.create', 'nickname_ar' => 'إضافة بند مصاريف', 'nickname_en' => 'Create Expense Item', 'group' => 'expenses'],
            ['name' => 'expense-items.edit', 'nickname_ar' => 'تعديل بند مصاريف', 'nickname_en' => 'Edit Expense Item', 'group' => 'expenses'],
            ['name' => 'expense-items.delete', 'nickname_ar' => 'حذف بند مصاريف', 'nickname_en' => 'Delete Expense Item', 'group' => 'expenses'],

            // General
            ['name' => 'general.settings', 'nickname_ar' => 'الإعدادات العامة', 'nickname_en' => 'General Settings', 'group' => 'general'],
            ['name' => 'general.backup', 'nickname_ar' => 'النسخ الاحتياطي', 'nickname_en' => 'Backup', 'group' => 'general'],
            ['name' => 'general.maintenance', 'nickname_ar' => 'وضع الصيانة', 'nickname_en' => 'Maintenance Mode', 'group' => 'general'],

            // Daily Reports
            ['name' => 'daily-reports.index', 'nickname_ar' => 'عرض التقارير اليومية', 'nickname_en' => 'View Daily Reports', 'group' => 'reports'],
            ['name' => 'daily-reports.create', 'nickname_ar' => 'إنشاء تقرير يومي', 'nickname_en' => 'Create Daily Report', 'group' => 'reports'],
            ['name' => 'daily-reports.edit', 'nickname_ar' => 'تعديل تقرير يومي', 'nickname_en' => 'Edit Daily Report', 'group' => 'reports'],
            ['name' => 'daily-reports.delete', 'nickname_ar' => 'حذف تقرير يومي', 'nickname_en' => 'Delete Daily Report', 'group' => 'reports'],

            // Payments
            ['name' => 'payments.index', 'nickname_ar' => 'عرض المدفوعات', 'nickname_en' => 'View Payments', 'group' => 'payments'],
            ['name' => 'payments.create', 'nickname_ar' => 'إضافة مدفوعة', 'nickname_en' => 'Create Payment', 'group' => 'payments'],
            ['name' => 'payments.edit', 'nickname_ar' => 'تعديل مدفوعة', 'nickname_en' => 'Edit Payment', 'group' => 'payments'],
            ['name' => 'payments.delete', 'nickname_ar' => 'حذف مدفوعة', 'nickname_en' => 'Delete Payment', 'group' => 'payments'],
            ['name' => 'payments.approve', 'nickname_ar' => 'اعتماد مدفوعة', 'nickname_en' => 'Approve Payment', 'group' => 'payments'],

            // Statistics
            ['name' => 'statistics.view', 'nickname_ar' => 'عرض الإحصائيات', 'nickname_en' => 'View Statistics', 'group' => 'statistics'],
            ['name' => 'statistics.export', 'nickname_ar' => 'تصدير الإحصائيات', 'nickname_en' => 'Export Statistics', 'group' => 'statistics'],

            // Meetings
            ['name' => 'meetings.index', 'nickname_ar' => 'عرض الاجتماعات', 'nickname_en' => 'View Meetings', 'group' => 'meetings'],
            ['name' => 'meetings.create', 'nickname_ar' => 'إنشاء اجتماع', 'nickname_en' => 'Create Meeting', 'group' => 'meetings'],
            ['name' => 'meetings.edit', 'nickname_ar' => 'تعديل اجتماع', 'nickname_en' => 'Edit Meeting', 'group' => 'meetings'],
            ['name' => 'meetings.delete', 'nickname_ar' => 'حذف اجتماع', 'nickname_en' => 'Delete Meeting', 'group' => 'meetings'],

            // Violations
            ['name' => 'violations.index', 'nickname_ar' => 'عرض المخالفات', 'nickname_en' => 'View Violations', 'group' => 'violations'],
            ['name' => 'violations.create', 'nickname_ar' => 'إضافة مخالفة', 'nickname_en' => 'Create Violation', 'group' => 'violations'],
            ['name' => 'violations.edit', 'nickname_ar' => 'تعديل مخالفة', 'nickname_en' => 'Edit Violation', 'group' => 'violations'],
            ['name' => 'violations.delete', 'nickname_ar' => 'حذف مخالفة', 'nickname_en' => 'Delete Violation', 'group' => 'violations'],

            // Financial Reports
            ['name' => 'financial-reports.index', 'nickname_ar' => 'عرض التقارير المالية', 'nickname_en' => 'View Financial Reports', 'group' => 'financial_reports'],
            ['name' => 'financial-reports.export', 'nickname_ar' => 'تصدير التقارير المالية', 'nickname_en' => 'Export Financial Reports', 'group' => 'financial_reports'],

            // Scheduling
            ['name' => 'scheduling.index', 'nickname_ar' => 'عرض الجدولة', 'nickname_en' => 'View Scheduling', 'group' => 'scheduling'],
            ['name' => 'scheduling.create', 'nickname_ar' => 'إنشاء جدولة', 'nickname_en' => 'Create Schedule', 'group' => 'scheduling'],
            ['name' => 'scheduling.edit', 'nickname_ar' => 'تعديل جدولة', 'nickname_en' => 'Edit Schedule', 'group' => 'scheduling'],
            ['name' => 'scheduling.delete', 'nickname_ar' => 'حذف جدولة', 'nickname_en' => 'Delete Schedule', 'group' => 'scheduling'],
        ];

        // Create new permissions
        foreach ($additional_permissions as $permissionData) {
            Permission::updateOrCreate(
                ['name' => $permissionData['name'], 'guard_name' => 'web'],
                [
                    'nickname_ar' => $permissionData['nickname_ar'],
                    'nickname_en' => $permissionData['nickname_en'],
                    'guard_name' => 'web'
                ]
            );
        }

        // create roles and assign created permissions
        $role = Role::updateOrCreate(
            ['name' => 'super-admin'],
            ['nickname_ar' => 'سوبر أدمن', 'nickname_en' => 'Super Admin']
        );
        Role::updateOrCreate(
            ['name' => 'admin'],
            ['nickname_ar' => 'أدمن', 'nickname_en' => 'Admin']
        );
        Role::updateOrCreate(
            ['name' => 'employee'],
            ['nickname_ar' => 'موظف', 'nickname_en' => 'Employee']
        );



        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'id' => 1,
                'name' => 'admin',
                'first_name' => 'admin',
                'last_name' => 'admin',
                'image' => Null,
                'is_email_verified' => 1,
                'is_phone_verified' => 0,
                'password' => 'admin',
                'remember_token' => NULL,
                'user_type' => 1,
                'is_active' => 1,
                'created_at' => Carbon::now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'id' => 2,
                'name' => 'manager',
                'first_name' => 'manager',
                'last_name' => 'manager',
                'image' => Null,
                'is_email_verified' => 1,
                'is_phone_verified' => 0,
                'password' => 'user',
                'remember_token' => NULL,
                'user_type' => 2,
                'is_active' => 1,
                'created_at' => Carbon::now(),
            ]
        );
        $role->givePermissionTo(Permission::all());
        $admin->assignRole('super-admin');
    }
}
