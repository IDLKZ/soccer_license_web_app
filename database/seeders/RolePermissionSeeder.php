<?php

namespace Database\Seeders;

use App\Constants\RoleConstants;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Admin Role
        $adminRole = Role::where('id', RoleConstants::ADMIN_ROLE_ID)->first();

        if ($adminRole) {
            // Get all user management permissions
            $userPermissions = Permission::whereIn('value', [
                'view-users',
                'create-users',
                'manage-users',
                'delete-users',
            ])->pluck('id')->toArray();

            // Sync permissions to admin role
            $adminRole->permissions()->syncWithoutDetaching($userPermissions);
        }
    }
}
