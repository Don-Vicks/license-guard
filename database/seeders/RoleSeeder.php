<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $editorRole = Role::create(['name' => 'editor']);
        $userRole = Role::create(['name' => 'user']);

        Permission::create(['name' => 'access-admin-panel']);
        Permission::create(['name' => 'edit-content']);
        Permission::create(['name' => 'view-content']);

        $adminRole->givePermissionTo(['access-admin-panel', 'edit-content', 'view-content']);
        $editorRole->givePermissionTo(['edit-content', 'view-content']);
        $userRole->givePermissionTo('view-content');
    }
}
