<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class NewPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Required permissions from requirements
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

            // Additional Financial System Permissions
            ['name' => 'financial.transfers.between.accounts', 'nickname_ar' => 'صندوق التحويل بين الحسابات مع جميع الأذونات ومعها النظام المالي - الحسابات', 'nickname_en' => 'Transfer between accounts box with all authorizations - Financial Accounts', 'group' => 'financial'],
            ['name' => 'financial.all.transactions.view', 'nickname_ar' => 'صندوق جميع المعاملات مع جميع الأذونات ومعها النظام المالي - الحسابات', 'nickname_en' => 'All transactions box with all authorizations - Financial Accounts', 'group' => 'financial'],
            ['name' => 'financial.accounts.data.view', 'nickname_ar' => 'صندوق بيانات الحسابات مع جميع الأذونات ومعها النظام المالي - الحسابات', 'nickname_en' => 'Accounts data box with all authorizations - Financial Accounts', 'group' => 'financial'],
            ['name' => 'financial.bookings.data.view', 'nickname_ar' => 'صندوق بيانات الحجوزات مع جميع الأذونات ومعها النظام المالي - الحسابات', 'nickname_en' => 'Bookings data box with all authorizations - Financial Accounts', 'group' => 'financial'],

            // Additional Expense Items Permissions
            ['name' => 'expense-items.view.all', 'nickname_ar' => 'عرض في صندوق جميع بنود المصاريف', 'nickname_en' => 'View in all expense items box', 'group' => 'expenses'],

            // General System Permissions
            ['name' => 'general.permissions.management', 'nickname_ar' => 'توفير صلاحيات ولكن مقيدة ومحتاج إلى إعادة تدريب وتنسيق', 'nickname_en' => 'Provide permissions but restricted and needs retraining and coordination', 'group' => 'general'],
            ['name' => 'general.restricted.permissions', 'nickname_ar' => 'توفير صلاحيات مقيدة تحتاج إلى حذف المكرر منها', 'nickname_en' => 'Provide restricted permissions that need duplicate removal', 'group' => 'general'],
            ['name' => 'general.bilingual.support', 'nickname_ar' => 'توفير جمل باللغة الإنجليزية بجانب جمل عربية تحتاج لمعالجة', 'nickname_en' => 'Provide English sentences alongside Arabic sentences that need processing', 'group' => 'general'],
            ['name' => 'general.english.permissions', 'nickname_ar' => 'توفير صلاحيات باللغة الإنجليزية تحتاج لمعالجة', 'nickname_en' => 'Provide English permissions that need processing', 'group' => 'general'],

            // Additional Customer Permissions
            ['name' => 'customers.bookings.actions', 'nickname_ar' => 'الحجوزات من صفحة جميع العملاء – زر الإجراءات', 'nickname_en' => 'Bookings from All Customers page - Actions button', 'group' => 'customers'],
            ['name' => 'customers.search.all', 'nickname_ar' => 'البحث من صفحة جميع العملاء', 'nickname_en' => 'Search from All Customers page', 'group' => 'customers'],
            ['name' => 'customers.notes.page', 'nickname_ar' => 'صفحة ملاحظات العملاء مع جميع الأزرار فيها', 'nickname_en' => 'Customer Notes page with all buttons', 'group' => 'customers'],
            ['name' => 'customers.note.types', 'nickname_ar' => 'صفحة أنواع الملاحظات مع جميع الأزرار فيها', 'nickname_en' => 'Note Types page with all buttons', 'group' => 'customers'],

            // Surveys Module (New)
            ['name' => 'surveys.index', 'nickname_ar' => 'عرض الاستبيانات', 'nickname_en' => 'View Surveys', 'group' => 'surveys'],
            ['name' => 'surveys.create', 'nickname_ar' => 'إنشاء استبيان', 'nickname_en' => 'Create Survey', 'group' => 'surveys'],
            ['name' => 'surveys.store', 'nickname_ar' => 'حفظ الاستبيان', 'nickname_en' => 'Store Survey', 'group' => 'surveys'],
            ['name' => 'surveys.edit', 'nickname_ar' => 'تعديل الاستبيان', 'nickname_en' => 'Edit Survey', 'group' => 'surveys'],
            ['name' => 'surveys.update', 'nickname_ar' => 'تحديث الاستبيان', 'nickname_en' => 'Update Survey', 'group' => 'surveys'],
            ['name' => 'surveys.destroy', 'nickname_ar' => 'حذف الاستبيان', 'nickname_en' => 'Delete Survey', 'group' => 'surveys'],
            ['name' => 'surveys.deleteAll', 'nickname_ar' => 'حذف جميع الاستبيانات', 'nickname_en' => 'Delete All Surveys', 'group' => 'surveys'],

            // Additional Inventory Permissions
            ['name' => 'inventory.search.all', 'nickname_ar' => 'البحث من صفحة جميع المخزون', 'nickname_en' => 'Search from All Inventory page', 'group' => 'stocks'],

            // Additional Bookings Permissions (21 permissions)
            ['name' => 'bookings.registration.forms', 'nickname_ar' => 'صفحة استمارات التسجيل، مع جميع الأزرار والحقول فيها', 'nickname_en' => 'Registration Forms page, with all its buttons and fields', 'group' => 'bookings'],
            ['name' => 'bookings.pending', 'nickname_ar' => 'صفحة الحجوزات المعلقة', 'nickname_en' => 'Pending Bookings page', 'group' => 'bookings'],
            ['name' => 'bookings.approved', 'nickname_ar' => 'صفحة الحجوزات المعتمدة', 'nickname_en' => 'Approved Bookings page', 'group' => 'bookings'],
            ['name' => 'bookings.canceled', 'nickname_ar' => 'صفحة الحجوزات الملغاة', 'nickname_en' => 'Canceled Bookings page', 'group' => 'bookings'],
            ['name' => 'bookings.postponed', 'nickname_ar' => 'صفحة الحجوزات المؤجلة', 'nickname_en' => 'Postponed Bookings page', 'group' => 'bookings'],
            ['name' => 'bookings.completed', 'nickname_ar' => 'صفحة الحجوزات المكتملة', 'nickname_en' => 'Completed Bookings page', 'group' => 'bookings'],
            ['name' => 'bookings.details', 'nickname_ar' => 'بيانات الحجز، وموقعها بعد الدخول على أي حجز', 'nickname_en' => 'Booking Details, located after entering any booking', 'group' => 'bookings'],
            ['name' => 'bookings.qr.scan', 'nickname_ar' => 'صفحة مسح رمز QR، من صفحة جميع الحجوزات', 'nickname_en' => 'QR Code Scan page, from the All Bookings page', 'group' => 'bookings'],
            ['name' => 'bookings.addons.approved.button', 'nickname_ar' => 'زر معتمد في صفحة الإضافات، وموقعها بعد الدخول على أي حجز', 'nickname_en' => 'Approved button on the Addons page, located after entering any booking', 'group' => 'bookings'],
            ['name' => 'bookings.addons.receipt', 'nickname_ar' => 'إيصال القبض في صفحة الإضافات، داخل زر الإجراءات', 'nickname_en' => 'Receipt on the Addons page, within the Actions button', 'group' => 'bookings'],
            ['name' => 'bookings.payments.approved.button', 'nickname_ar' => 'زر معتمد في صفحة المدفوعات، وموقعها بعد الدخول على أي حجز', 'nickname_en' => 'Approved button on the Payments page, located after entering any booking', 'group' => 'bookings'],
            ['name' => 'bookings.payments.receipt', 'nickname_ar' => 'إيصال القبض في صفحة المدفوعات، داخل زر الإجراءات', 'nickname_en' => 'Receipt on the Payments page, within the Actions button', 'group' => 'bookings'],
            ['name' => 'bookings.payment.links', 'nickname_ar' => 'صفحة روابط الدفع مع جميع الأزرار فيها، وموقعها بعد الدخول على أي حجز', 'nickname_en' => 'Payment Links page with all its buttons, located after entering any booking', 'group' => 'bookings'],
            ['name' => 'bookings.payment.links.view', 'nickname_ar' => 'صفحة عرض روابط الدفع مع جميع الأزرار فيها، وموقعها بعد الدخول على أي حجز', 'nickname_en' => 'View Payment Links page with all its buttons, located after entering any booking', 'group' => 'bookings'],
            ['name' => 'bookings.inventory.sales', 'nickname_ar' => 'صفحة مبيعات المخزن مع جميع الأزرار فيها، وموقعها بعد الدخول على أي حجز', 'nickname_en' => 'Inventory Sales page with all its buttons, located after entering any booking', 'group' => 'bookings'],
            ['name' => 'bookings.refund.security', 'nickname_ar' => 'صفحة رد / مصادرة التأمين مع جميع الأزرار فيها، وموقعها بعد الدخول على أي حجز', 'nickname_en' => 'Refund / Security Deposit Forfeiture page with all its buttons, located after entering any booking', 'group' => 'bookings'],
            ['name' => 'bookings.reports', 'nickname_ar' => 'صفحة التقارير مع جميع الأزرار فيها، وموقعها بعد الدخول على أي حجز', 'nickname_en' => 'Reports page with all its buttons, located after entering any booking', 'group' => 'bookings'],
            ['name' => 'bookings.approved.details', 'nickname_ar' => 'صفحة بيانات الحجز، وموقعها بعد الدخول على أي حجز معتمد', 'nickname_en' => 'Booking Data page, located after entering any approved booking', 'group' => 'bookings'],
            ['name' => 'bookings.invoice', 'nickname_ar' => 'صفحة الفاتورة، وموقعها بعد الدخول على أي حجز', 'nickname_en' => 'Invoice page, located after entering any booking', 'group' => 'bookings'],
            ['name' => 'bookings.price.quote', 'nickname_ar' => 'صفحة عرض السعر، وموقعها بعد الدخول على أي حجز', 'nickname_en' => 'Price Quote page, located after entering any booking', 'group' => 'bookings'],
            ['name' => 'bookings.terms.conditions', 'nickname_ar' => 'صفحة موافقة الشروط والأحكام، وموقعها بعد الدخول على أي حجز', 'nickname_en' => 'Terms and Conditions Approval page, located after entering any booking', 'group' => 'bookings'],

            // Tasks Module (New)
            ['name' => 'tasks.index', 'nickname_ar' => 'عرض المهام', 'nickname_en' => 'View Tasks', 'group' => 'tasks'],
            ['name' => 'tasks.create', 'nickname_ar' => 'إنشاء مهمة', 'nickname_en' => 'Create Task', 'group' => 'tasks'],
            ['name' => 'tasks.store', 'nickname_ar' => 'حفظ المهمة', 'nickname_en' => 'Store Task', 'group' => 'tasks'],
            ['name' => 'tasks.edit', 'nickname_ar' => 'تعديل المهمة', 'nickname_en' => 'Edit Task', 'group' => 'tasks'],
            ['name' => 'tasks.update', 'nickname_ar' => 'تحديث المهمة', 'nickname_en' => 'Update Task', 'group' => 'tasks'],
            ['name' => 'tasks.destroy', 'nickname_ar' => 'حذف المهمة', 'nickname_en' => 'Delete Task', 'group' => 'tasks'],
            ['name' => 'tasks.deleteAll', 'nickname_ar' => 'حذف جميع المهام', 'nickname_en' => 'Delete All Tasks', 'group' => 'tasks'],

            // Equipment Location List Module (New)
            ['name' => 'equipment.index', 'nickname_ar' => 'عرض قائمة موقع المعدات', 'nickname_en' => 'View Equipment Location List', 'group' => 'equipment'],
            ['name' => 'equipment.create', 'nickname_ar' => 'إنشاء موقع معدات', 'nickname_en' => 'Create Equipment Location', 'group' => 'equipment'],
            ['name' => 'equipment.store', 'nickname_ar' => 'حفظ موقع المعدات', 'nickname_en' => 'Store Equipment Location', 'group' => 'equipment'],
            ['name' => 'equipment.edit', 'nickname_ar' => 'تعديل موقع المعدات', 'nickname_en' => 'Edit Equipment Location', 'group' => 'equipment'],
            ['name' => 'equipment.update', 'nickname_ar' => 'تحديث موقع المعدات', 'nickname_en' => 'Update Equipment Location', 'group' => 'equipment'],
            ['name' => 'equipment.destroy', 'nickname_ar' => 'حذف موقع المعدات', 'nickname_en' => 'Delete Equipment Location', 'group' => 'equipment'],
            ['name' => 'equipment.deleteAll', 'nickname_ar' => 'حذف جميع مواقع المعدات', 'nickname_en' => 'Delete All Equipment Locations', 'group' => 'equipment'],

            // Camp Status Reports Module (New)
            ['name' => 'camp-reports.index', 'nickname_ar' => 'عرض تقارير حالة المخيمات', 'nickname_en' => 'View Camps Status Reports', 'group' => 'camp-reports'],
            ['name' => 'camp-reports.create', 'nickname_ar' => 'إنشاء تقرير حالة مخيم', 'nickname_en' => 'Create Camp Status Report', 'group' => 'camp-reports'],
            ['name' => 'camp-reports.store', 'nickname_ar' => 'حفظ تقرير حالة المخيم', 'nickname_en' => 'Store Camp Status Report', 'group' => 'camp-reports'],
            ['name' => 'camp-reports.edit', 'nickname_ar' => 'تعديل تقرير حالة المخيم', 'nickname_en' => 'Edit Camp Status Report', 'group' => 'camp-reports'],
            ['name' => 'camp-reports.update', 'nickname_ar' => 'تحديث تقرير حالة المخيم', 'nickname_en' => 'Update Camp Status Report', 'group' => 'camp-reports'],
            ['name' => 'camp-reports.destroy', 'nickname_ar' => 'حذف تقرير حالة المخيم', 'nickname_en' => 'Delete Camp Status Report', 'group' => 'camp-reports'],
            ['name' => 'camp-reports.deleteAll', 'nickname_ar' => 'حذف جميع تقارير حالة المخيمات', 'nickname_en' => 'Delete All Camp Status Reports', 'group' => 'camp-reports'],

            // Terms Settings Module (New)
            ['name' => 'terms-settings.index', 'nickname_ar' => 'عرض إعدادات الشروط', 'nickname_en' => 'View Terms Settings', 'group' => 'terms-settings'],
            ['name' => 'terms-settings.create', 'nickname_ar' => 'إنشاء إعداد شروط', 'nickname_en' => 'Create Terms Setting', 'group' => 'terms-settings'],
            ['name' => 'terms-settings.store', 'nickname_ar' => 'حفظ إعداد الشروط', 'nickname_en' => 'Store Terms Setting', 'group' => 'terms-settings'],
            ['name' => 'terms-settings.edit', 'nickname_ar' => 'تعديل إعداد الشروط', 'nickname_en' => 'Edit Terms Setting', 'group' => 'terms-settings'],
            ['name' => 'terms-settings.update', 'nickname_ar' => 'تحديث إعداد الشروط', 'nickname_en' => 'Update Terms Setting', 'group' => 'terms-settings'],
            ['name' => 'terms-settings.destroy', 'nickname_ar' => 'حذف إعداد الشروط', 'nickname_en' => 'Delete Terms Setting', 'group' => 'terms-settings'],
            ['name' => 'terms-settings.deleteAll', 'nickname_ar' => 'حذف جميع إعدادات الشروط', 'nickname_en' => 'Delete All Terms Settings', 'group' => 'terms-settings'],
        ];

        // Create permissions
        foreach ($additional_permissions as $permissionData) {
            $permission = Permission::where('name', $permissionData['name'])->first();

            if (!$permission) {
                Permission::create([
                    'name' => $permissionData['name'],
                    'nickname_ar' => $permissionData['nickname_ar'],
                    'nickname_en' => $permissionData['nickname_en'],
                    'guard_name' => 'web'
                ]);
            } else {
                // Update existing permission with new nicknames
                $permission->update([
                    'nickname_ar' => $permissionData['nickname_ar'],
                    'nickname_en' => $permissionData['nickname_en'],
                ]);
            }
        }

        $this->command->info('Added ' . count($additional_permissions) . ' new permissions successfully!');
    }
}
