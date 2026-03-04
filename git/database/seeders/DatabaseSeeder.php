<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RolesAndPermissionsSeeder::class);
        
        $admin = Admin::create([
            'email' => 'admin@admin.com',
            'password' => Hash::make(123456789),
        ]);

        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);

        $admin->assignRole($role);
        $role->givePermissionTo(Permission::all());

    }
}
