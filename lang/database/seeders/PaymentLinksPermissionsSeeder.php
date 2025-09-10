<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PaymentLinksPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create payment-links permissions if they don't exist
        $permissions = [
            'payment-links.index',
            'payment-links.create',
            'payment-links.store',
            'payment-links.show',
            'payment-links.resend',
            'payment-links.cancel',
            'payment-links.destroy',
            'payment-links.qr-code',
            'payment-links.copy',
            'payment-links.update-status'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign payment-links permissions to admin roles
        $adminRoles = ['super-admin', 'admin'];

        foreach ($adminRoles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                foreach ($permissions as $permission) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }
}
