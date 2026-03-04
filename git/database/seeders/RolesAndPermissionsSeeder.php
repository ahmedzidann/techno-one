<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $controllers = [
            'المستخدمين',
            'الفروع',
            'العملاء',
            'إعدادات تسديد العملاء',
            'الشركات',
            'مرتجعات المشتريات',
            'مرتجعات المبيعات',
            'الاصناف',
            'المشتريات',
            'الفواتير',
            'الاعدادات',
            'المخازن',
            'المسئولون عن المخازن',
            'الموردين',
            'الوحدات',
            'الموظفون',
            'الادوار',
            'الاهلاك',
            'المرتجعات',
            'المناديب',
            'التسوية',
            'طلبات الشراء',
        ];

        $actions = ['عرض', 'إنشاء', 'تعديل', 'حذف', 'تفاصيل'];

        foreach ($controllers as $controller) {
            foreach ($actions as $action) {
                Permission::updateOrCreate(['name' => "{$action} {$controller}"], ['name' => "{$action} {$controller}", 'name_slug' => "{$action} {$controller}", 'group' => $controller, 'group_slug' => $controller, 'guard_name' => 'admin']);
            }
        }

        $customs = [
            'تحضير الاصناف' => 'الاصناف',
        ]; // add your custom permissions here

        foreach ($customs as $name => $group) {
            Permission::updateOrCreate(['name' => $name], ['name' => $name, 'name_slug' => $name, 'group' => $group, 'group_slug' => $group, 'guard_name' => 'admin']);
        }

    }
}
