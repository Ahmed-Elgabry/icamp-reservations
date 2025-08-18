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
            if ($route->getName()){
                // Skip duplicate route names
                if (in_array($route->getName(), $seen_names)) {
                    continue;
                }

                $seen_names[] = $route->getName();
                $routes_data []   = [ 'name' => $route->getName() ,
                'nickname_en' =>  $route->getName() ,
                'nickname_ar' =>  $route->getName() ,
                'guard_name' => 'web'
            ];
            }
        }
        Permission::insert( $routes_data );

        // create roles and assign created permissions
        $role = Role::create(['name' => 'super-admin' ,'nickname_ar' => 'سوبر أدمن' , 'nickname_en' => 'Super Admin']);
        Role::create(['name' => 'admin'  ,'nickname_ar' => 'أدمن' , 'nickname_en' => 'Admin']);
        Role::create(['name' => 'employee'    ,'nickname_ar' => 'موظف' , 'nickname_en' => 'Employee']);



        $admin = User::create(
            [
                'id' => 1,
                'name' => 'admin',
                'first_name' => 'admin',
                'last_name' => 'admin',
                'image' => Null,
                'email' => 'admin@gmail.com',
                'is_email_verified' => 1,
                'is_phone_verified' => 0,
                'password' => 'admin',
                'remember_token' => NULL,
                'user_type' => 1,
                'is_active' => 1,
                'created_at' => Carbon::now(),
            ]

        );

        User::create([

                'id' => 2,
                'name' => 'manager',
                'first_name' => 'manager',
                'last_name' => 'manager',
                'image' => Null,
                'email' => 'manager@gmail.com',
                'is_email_verified' => 1,
                'is_phone_verified' => 0,
                'password' => 'user',
                'remember_token' => NULL,
                'user_type' => 2,
                'is_active' => 1,
                'created_at' => Carbon::now(),
            ]);
        $role->givePermissionTo(Permission::all());
        $admin->assignRole('super-admin');
    }
}
