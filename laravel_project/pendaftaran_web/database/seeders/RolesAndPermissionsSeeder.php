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
        //    This is CRUCIAL if you run the seeder multiple times during development
        //    BE CAREFUL: This deletes all existing roles and permissions!
        Role::query()->delete();
        Permission::query()->delete();


        // 3. Create Permissions - ADD 'guard_name' => 'web' to EACH ONE
        Permission::create(['name' => 'create users', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit users', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete users', 'guard_name' => 'web']);
        Permission::create(['name' => 'view users', 'guard_name' => 'web']);

        Permission::create(['name' => 'create posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'publish posts', 'guard_name' => 'web']);
        Permission::create(['name' => 'view posts', 'guard_name' => 'web']);

        // 4. Create Roles - ADD 'guard_name' => 'web' to EACH ONE
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $editorRole = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $userRole = Role::create(['name' => 'user', 'guard_name' => 'web']);


        // 5. Assign Permissions to Roles
        //    Now assign permissions to roles, as permissions definitely exist and have guards
        $adminRole->givePermissionTo(Permission::all()); // Admin gets all permissions

        $editorRole->givePermissionTo(['view posts', 'create posts', 'edit posts', 'publish posts']);

        $userRole->givePermissionTo(['view posts']); // Basic users can only view posts


        // 6. Assign roles to existing users (example)
        //    Ensure these users actually exist in your database before running
        $user1 = User::find(1); // Assuming user with ID 1 exists
        if ($user1) {
            $user1->assignRole('admin');
        }

        $user2 = User::find(2); // Assuming user with ID 2 exists
        if ($user2) {
            $user2->assignRole('editor');
        }

        $user3 = User::find(3); // Assuming user with ID 3 exists
        if ($user3) {
            $user3->assignRole('user');
        }
    }
}