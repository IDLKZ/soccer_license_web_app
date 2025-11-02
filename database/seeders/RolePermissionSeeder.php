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
            // Get all system permissions (User, Role, and Permission Management)
            $allPermissions = Permission::where('is_system', true)->pluck('id')->toArray();

            // Sync all permissions to admin role
            $adminRole->permissions()->syncWithoutDetaching($allPermissions);
        }

        // Get Licensing Department Role
        $licensingDepartmentRole = Role::where('value', 'licensing-department')->first();

        if ($licensingDepartmentRole) {
            // Get specific permissions for licensing department
            $licensingPermissions = Permission::whereIn('value', [
                'view-category-documents',
                'create-category-documents',
                'manage-category-documents',
                'delete-category-documents',
                'view-documents',
                'create-documents',
                'manage-documents',
                'delete-documents',
                'view-application-status-categories',
                'create-application-status-categories',
                'manage-application-status-categories',
                'delete-application-status-categories',
                'view-application-statuses',
                'create-application-statuses',
                'manage-application-statuses',
                'delete-application-statuses',
                'view-club-teams',
                'create-club-teams',
                'manage-club-teams',
                'delete-club-teams',
                'view-licences',
                'create-licences',
                'manage-licences',
                'delete-licences',
                'view-licence-requirements',
                'create-licence-requirements',
                'manage-licence-requirements',
                'delete-licence-requirements',
                'view-licence-deadlines',
                'create-licence-deadlines',
                'manage-licence-deadlines',
                'delete-licence-deadlines',
            ])->pluck('id')->toArray();

            // Sync permissions to licensing department role
            $licensingDepartmentRole->permissions()->syncWithoutDetaching($licensingPermissions);
        }

        // Get Club Administrator Role
        $clubAdministratorRole = Role::where('value', RoleConstants::CLUB_ADMINISTRATOR_VALUE)->first();

        if ($clubAdministratorRole) {
            // Get specific permissions for club administrators
            $clubPermissions = Permission::whereIn('value', [
                'view-clubs',
                'create-clubs',
                'manage-clubs',
                'delete-clubs',
                'view-club-teams',
                'create-club-teams',
                'manage-club-teams',
                'delete-club-teams',
                'apply-for-license',
            ])->pluck('id')->toArray();

            // Sync permissions to club administrator role
            $clubAdministratorRole->permissions()->syncWithoutDetaching($clubPermissions);
        }
    }
}
