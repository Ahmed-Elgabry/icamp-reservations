<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

trait Roles
{
    function addRole()
    {
        return $this->generatePermissionHTML();
    }

    private function generatePermissionHTML($selectedPermissions = [])
    {
        // Get all permissions from database and group them
        $allPermissions = Permission::all();
        $permissionGroups = [];

        // Define group mappings
        $groupMappings = [
            'customers' => ['title_ar' => 'العملاء', 'title_en' => 'Customers'],
            'strategies' => ['title_ar' => 'الاستراتيجيات', 'title_en' => 'Strategies'],
            'inventory' => ['title_ar' => 'المخزون', 'title_en' => 'Inventory'],
            'addons' => ['title_ar' => 'الإضافات', 'title_en' => 'Addons'],
            'camp-types' => ['title_ar' => 'نوع المخيم', 'title_en' => 'Camp Types'],
            'bookings' => ['title_ar' => 'الحجوزات', 'title_en' => 'Bookings'],
            'orders' => ['title_ar' => 'الطلبات', 'title_en' => 'Orders'],
            'financial' => ['title_ar' => 'النظام المالي', 'title_en' => 'Financial System'],
            'expense-items' => ['title_ar' => 'بنود المصاريف', 'title_en' => 'Expense Items'],
            'expenses' => ['title_ar' => 'المصاريف', 'title_en' => 'Expenses'],
            'general' => ['title_ar' => 'العام', 'title_en' => 'General'],
            'daily-reports' => ['title_ar' => 'التقارير اليومية', 'title_en' => 'Daily Reports'],
            'payments' => ['title_ar' => 'المدفوعات', 'title_en' => 'Payments'],
            'statistics' => ['title_ar' => 'الإحصائيات', 'title_en' => 'Statistics'],
            'meetings' => ['title_ar' => 'الاجتماعات', 'title_en' => 'Meetings'],
            'violations' => ['title_ar' => 'المخالفات', 'title_en' => 'Violations'],
            'financial-reports' => ['title_ar' => 'التقارير المالية', 'title_en' => 'Financial Reports'],
            'scheduling' => ['title_ar' => 'الجدولة', 'title_en' => 'Scheduling'],
            'admins' => ['title_ar' => 'الإدارة', 'title_en' => 'Administration'],
            'roles' => ['title_ar' => 'الصلاحيات', 'title_en' => 'Roles'],
            'users' => ['title_ar' => 'المستخدمين', 'title_en' => 'Users'],
            'banners' => ['title_ar' => 'البانرات', 'title_en' => 'Banners'],
            'categories' => ['title_ar' => 'الفئات', 'title_en' => 'Categories'],
            'cities' => ['title_ar' => 'المدن', 'title_en' => 'Cities'],
            'stocks' => ['title_ar' => 'المخزون', 'title_en' => 'Stocks'],
            'surveys' => ['title_ar' => 'الاستبيانات', 'title_en' => 'Surveys'],
            'questions' => ['title_ar' => 'الأسئلة', 'title_en' => 'Questions'],
            'banks' => ['title_ar' => 'البنوك', 'title_en' => 'Banks'],
            'tasks' => ['title_ar' => 'المهام', 'title_en' => 'Tasks'],
            'equipment' => ['title_ar' => 'قائمة موقع المعدات', 'title_en' => 'Equipment Location List'],
            'camp-reports' => ['title_ar' => 'تقارير حالة المخيمات', 'title_en' => 'Camps Status Reports'],
            'terms-settings' => ['title_ar' => 'إعدادات الشروط', 'title_en' => 'Terms Settings'],
            'other' => ['title_ar' => 'أخرى', 'title_en' => 'Other'],
        ];

        // Special mappings for permissions that don't follow the standard prefix pattern
        $specialMappings = [
            // Orders/Bookings related
            'orders.quote' => 'bookings',
            'orders.invoice' => 'bookings',
            'orders.rate' => 'bookings',
            'orders.registeration-forms' => 'bookings',
            'orders.customers.check' => 'bookings',
            'order.verified' => 'bookings',

            // All the permissions from the screenshot that should go to bookings
            'استخدام صلاحية صندوق (استمارات التسجيل) مع جميع الأذونات والدخول فيها' => 'bookings',
            'استخدام صلاحية صندوق (الحجوزات المعلقة)' => 'bookings',
            'استخدام صلاحية صندوق (الحجوزات المعتمدة)' => 'bookings',
            'استخدام صلاحية صندوق (الحجوزات الملغاة)' => 'bookings',
            'استخدام صلاحية صندوق (الحجوزات المؤجلة)' => 'bookings',
            'استخدام صلاحية صندوق (الحجوزات المكتملة)' => 'bookings',
            'استخدام صلاحية (بيانات الحجز) ومعها بعد الدخول على أي حجز' => 'bookings',
            'استخدام صلاحية صندوق (مسبق بدء QR) على أي حجز' => 'bookings',
            'استخدام صلاحية بر (معتمدي) في صندوق الإضافات ومعها بعد الدخول على أي حجز' => 'bookings',
            'استخدام صلاحية (إرسال الرقم) في صندوق الإضافات داخل تفاصيل الحجوزات' => 'bookings',
            'استخدام صلاحية بر (معتمدي) في صندوق المدفوعات ومعها بعد الدخول على أي حجز' => 'bookings',
            'استخدام صلاحية (إرسال الرقم) في صندوق المدفوعات داخل تفاصيل الحجوزات' => 'bookings',
            'استخدام صلاحية (رواتب الدفع) مع جميع الأذونات فيها ومعها بعد الدخول على أي حجز' => 'bookings',
            'استخدام صلاحية (عرض رواتب الدفع) مع جميع الأذونات فيها ومعها بعد الدخول على أي حجز' => 'bookings',
            'استخدام صلاحية (مبيعات المخدم) مع جميع الأذونات فيها ومعها بعد الدخول على أي حجز' => 'bookings',
            'استخدام صلاحية صندوق ( د / مشاهدة الرقم) مع جميع الأذونات فيها ومعها بعد الدخول على أي حجز' => 'bookings',
            'استخدام صلاحية صندوق (التقارير) مع جميع الأذونات فيها الآذن الرئيسية في الصفحة' => 'bookings',
            'غير مطلوب في ملاحظات نقطة الأذونات الرئيسية في الصفحة' => 'bookings',
            'استخدام صلاحية (بيانات الحجز) ومعها بعد الدخول على أي حجز معتمد' => 'bookings',
            'استخدام صلاحية (الفاتورة) ومعها بعد الدخول على أي حجز' => 'bookings',
            'استخدام صلاحية (عرض السعر) ومعها بعد الدخول على أي حجز' => 'bookings',
            'استخدام صلاحية (مطابقة الشروط والأحكام) ومعها بعد الدخول على أي حجز' => 'bookings',

            // Add more as needed
        ];

        // Group permissions by their prefix
        foreach ($allPermissions as $permission) {
            $permissionName = $permission->name;
            $groupKey = 'other'; // Default group

            // Check special mappings first
            if (isset($specialMappings[$permissionName])) {
                $groupKey = $specialMappings[$permissionName];
            } else {
                // Find the appropriate group based on permission name prefix
                foreach ($groupMappings as $key => $mapping) {
                    if (strpos($permissionName, $key . '.') === 0) {
                        $groupKey = $key;
                        break;
                    }
                }

                // Additional pattern matching for Arabic permissions
                if ($groupKey === 'other') {
                    // Check for booking-related Arabic text
                    if (
                        strpos($permissionName, 'حجز') !== false ||
                        strpos($permissionName, 'الحجوزات') !== false ||
                        strpos($permissionName, 'استمارات التسجيل') !== false
                    ) {
                        $groupKey = 'bookings';
                    }
                    // Check for orders-related text
                    elseif (strpos($permissionName, 'orders.') === 0 || strpos($permissionName, 'order.') === 0) {
                        $groupKey = 'bookings';
                    }
                    // Check for customers-related text
                    elseif (
                        strpos($permissionName, 'العملاء') !== false ||
                        strpos($permissionName, 'عميل') !== false ||
                        strpos($permissionName, 'العميل') !== false
                    ) {
                        $groupKey = 'customers';
                    }
                    // Check for strategies-related text
                    elseif (
                        strpos($permissionName, 'الاستراتيجيات') !== false ||
                        strpos($permissionName, 'استراتيجية') !== false
                    ) {
                        $groupKey = 'strategies';
                    }
                    // Check for inventory/stock-related text
                    elseif (
                        strpos($permissionName, 'المخزون') !== false ||
                        strpos($permissionName, 'مخزون') !== false ||
                        strpos($permissionName, 'المخدم') !== false
                    ) {
                        $groupKey = 'inventory';
                    }
                    // Check for addons-related text (excluding booking-related addons)
                    elseif (strpos($permissionName, 'الإضافات') !== false && strpos($permissionName, 'حجز') === false) {
                        $groupKey = 'addons';
                    }
                    // Check for camp types-related text
                    elseif (
                        strpos($permissionName, 'نوع المخيم') !== false ||
                        strpos($permissionName, 'أنواع المخيم') !== false
                    ) {
                        $groupKey = 'camp-types';
                    }
                    // Check for meetings-related text
                    elseif (
                        strpos($permissionName, 'اجتماع') !== false ||
                        strpos($permissionName, 'الاجتماعات') !== false
                    ) {
                        $groupKey = 'meetings';
                    }
                    // Check for violations-related text
                    elseif (
                        strpos($permissionName, 'مخالفة') !== false ||
                        strpos($permissionName, 'المخالفات') !== false
                    ) {
                        $groupKey = 'violations';
                    }
                    // Check for payments-related text
                    elseif (
                        strpos($permissionName, 'مدفوعات') !== false ||
                        strpos($permissionName, 'الدفع') !== false ||
                        strpos($permissionName, 'رواتب الدفع') !== false
                    ) {
                        $groupKey = 'payments';
                    }
                    // Check for expenses-related text
                    elseif (
                        strpos($permissionName, 'المصاريف') !== false ||
                        strpos($permissionName, 'مصاريف') !== false ||
                        strpos($permissionName, 'بنود المصاريف') !== false
                    ) {
                        $groupKey = 'expenses';
                    }
                    // Check for reports-related text
                    elseif (
                        strpos($permissionName, 'التقارير') !== false ||
                        strpos($permissionName, 'تقرير') !== false
                    ) {
                        $groupKey = 'daily-reports';
                    }
                    // Check for statistics-related text
                    elseif (
                        strpos($permissionName, 'إحصائيات') !== false ||
                        strpos($permissionName, 'الإحصائيات') !== false
                    ) {
                        $groupKey = 'statistics';
                    }
                    // Check for financial system specific patterns
                    elseif (
                        strpos($permissionName, 'التحويل بين الحسابات') !== false ||
                        strpos($permissionName, 'جميع المعاملات') !== false ||
                        strpos($permissionName, 'بيانات الحسابات') !== false ||
                        strpos($permissionName, 'النظام المالي') !== false
                    ) {
                        $groupKey = 'financial';
                    }
                    // Check for general system management patterns
                    elseif (
                        strpos($permissionName, 'توفير صلاحيات') !== false ||
                        strpos($permissionName, 'إعادة تدريب') !== false ||
                        strpos($permissionName, 'باللغة الإنجليزية') !== false ||
                        strpos($permissionName, 'تحتاج لمعالجة') !== false
                    ) {
                        $groupKey = 'general';
                    }
                }
            }

            // Initialize group if not exists
            if (!isset($permissionGroups[$groupKey])) {
                $permissionGroups[$groupKey] = [
                    'title_ar' => $groupMappings[$groupKey]['title_ar'] ?? 'أخرى',
                    'title_en' => $groupMappings[$groupKey]['title_en'] ?? 'Other',
                    'permissions' => []
                ];
            }

            // Get translation from database nickname fields
            $permissionTitleAr = !empty($permission->nickname_ar) ? $permission->nickname_ar : $permissionName;
            $permissionTitleEn = !empty($permission->nickname_en) ? $permission->nickname_en : $permissionName;

            // Add permission to group
            $permissionGroups[$groupKey]['permissions'][$permissionName] = [
                'ar' => $permissionTitleAr,
                'en' => $permissionTitleEn,
            ];
        }

        // Remove empty groups
        $permissionGroups = array_filter($permissionGroups, function ($group) {
            return !empty($group['permissions']);
        });

        $html = '';
        $id = 0;

        foreach ($permissionGroups as $groupKey => $group) {
            $parent_class = 'group_' . $groupKey . '_' . $id++;
            // Use current app locale
            $groupTitle = app()->getLocale() == 'ar' ? $group['title_ar'] : $group['title_en'];

            $html .= '
                    <div class="col-md-4">
                        <div class="card permissionCard package bg-white shadow">
                            <div class="role-title text-white" style="display: flex; justify-content: space-between;">
                                <div style="display: flex; flex-direction: row; align-items: center">
                                    <div class="icheck-primary d-inline">
                                    <input type="checkbox" class="group-parent" data-group="' . $parent_class . '" id="' . $parent_class . '_parent">
                                    <label for="' . $parent_class . '_parent" dir="ltr"></label>
                                    </div>
                                <p class="text-white selectP">' . $groupTitle . '</p>
                                </div>
                                <div style="display: flex; flex-direction: row-reverse; align-items: center">
                                    <p class="text-white selectP">' . __("dashboard.select_all") . '</p>
                                    <input type="checkbox" class="checkChilds checkChilds_' . $parent_class . '" data-parent="' . $parent_class . '">
                                </div>
                        </div>
                        <div class="card permissionCard bg-white shadow">
                            <ul class="list-unstyled">';

            foreach ($group['permissions'] as $permissionName => $permissionTitle) {
                $isSelected = in_array($permissionName, $selectedPermissions) ? 'checked' : '';
                // Use current app locale
                $title = app()->getLocale() == 'ar' ? $permissionTitle['ar'] : $permissionTitle['en'];

                $html .= '
                            <li>
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                <input type="checkbox" name="permissions[]" data-parent="' . $parent_class . '" value="' . $permissionName . '" id="' . $permissionName . '" class="childs ' . $parent_class . '" ' . $isSelected . '>
                                <label for="' . $permissionName . '" dir="ltr"></label>
                                    </div>
                            <label class="title_lable" for="' . $permissionName . '">' . $title . '</label>
                                </div>
                            </li>';
            }

            $html .= '
                            </ul>
                        </div>
                    </div>
                </div>';
        }

        return $html;
    }

    // editRole
    function editRole($id)
    {
        $role = Role::with('permissions')->find($id);
        $selectedPermissions = $role->permissions()->pluck('name')->toArray();

        return $this->generatePermissionHTML($selectedPermissions);
    }
}
