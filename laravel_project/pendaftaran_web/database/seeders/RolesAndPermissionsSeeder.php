<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // Assuming your User model is in App\Models

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Reset cached roles and permissions - ALWAYS DO THIS FIRST
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Clear existing permissions and roles to prevent duplicates on re-seed
        Role::query()->delete();
        Permission::query()->delete();


        // 3. Create Permissions
        Permission::create(['name' => 'create users', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit users', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete users', 'guard_name' => 'web']);
        Permission::create(['name' => 'view users', 'guard_name' => 'web']);

        Permission::create(['name' => 'create posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'publish posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'view posts', 'guard_name' => 'web']);

        // --- NEW: Permissions for Settings ---
        Permission::create(['name' => 'view settings', 'guard_name' => 'web']);
        Permission::create(['name' => 'manage settings', 'guard_name' => 'web']); // Broader permission if needed
        // --- END NEW ---


        // 4. Create Roles
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $editorRole = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $operatorRole = Role::create(['name' => 'operator', 'guard_name' => 'web']);


        // 5. Assign Permissions to Roles
        $adminRole->givePermissionTo(Permission::all()); // Admin gets ALL permissions (including new ones)

        $editorPermissions = ['view posts', 'create posts', 'edit posts', 'publish posts'];
        $editorRole->givePermissionTo($editorPermissions);

        $userRole->givePermissionTo(['view posts']);

        $operatorRole->givePermissionTo($editorPermissions); // Operator gets same permissions as editor


        // 6. Assign roles to existing users (example)
        $user1 = User::find(1);
        if ($user1) {
            $user1->assignRole('admin');
        }

        $user2 = User::find(2);
        if ($user2) {
            $user2->assignRole('editor');
        }

        $user3 = User::find(3);
        if ($user3) {
            $user3->assignRole('user');
        }

        $user4 = User::find(4);
        if ($user4) {
            $user4->assignRole('operator');
        }
    }
}
