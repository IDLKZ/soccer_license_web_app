<?php

use App\Constants\RoleConstants;

if (!function_exists('get_user_layout')) {
    /**
     * Get layout based on user role
     *
     * @return string
     */
    function get_user_layout(): string
    {
        if (!auth()->check()) {
            return 'layouts.guest';
        }

        $user = auth()->user();
        $role = $user->role;

        if (!$role) {
            return 'layouts.guest';
        }

        // Admin layout
        if ($role->value === RoleConstants::ADMIN_ROLE_VALUE) {
            return 'layouts.admin';
        }

        // Department layout (administrative roles except admin)
        if ($role->is_administrative && $role->value !== RoleConstants::ADMIN_ROLE_VALUE) {
            return 'layouts.department';
        }

        // Club layout (non-administrative roles)
        if (!$role->is_administrative) {
            return 'layouts.club';
        }

        return 'layouts.guest';
    }
}

if (!function_exists('get_role_icon')) {
    /**
     * Get icon for role
     *
     * @param string $roleValue
     * @return string
     */
    function get_role_icon(string $roleValue): string
    {
        return match ($roleValue) {
            RoleConstants::ADMIN_ROLE_VALUE => 'fa-shield-halved',
            RoleConstants::CLUB_ADMINISTRATOR_VALUE => 'fa-futbol',
            RoleConstants::LICENSING_DEPARTMENT_VALUE => 'fa-clipboard-check',
            RoleConstants::LEGAL_DEPARTMENT_VALUE => 'fa-gavel',
            RoleConstants::FINANCE_DEPARTMENT_VALUE => 'fa-coins',
            RoleConstants::INFRASTRUCTURE_DEPARTMENT_VALUE => 'fa-building',
            RoleConstants::CONTROL_DEPARTMENT_VALUE => 'fa-search',
            RoleConstants::LEGAL_SPECIALIST_VALUE => 'fa-balance-scale',
            RoleConstants::FINANCIAL_SPECIALIST_VALUE => 'fa-calculator',
            RoleConstants::SPORTING_DIRECTOR_VALUE => 'fa-trophy',
            default => 'fa-user',
        };
    }
}

if (!function_exists('get_role_color')) {
    /**
     * Get color class for role
     *
     * @param string $roleValue
     * @return string
     */
    function get_role_color(string $roleValue): string
    {
        return match ($roleValue) {
            RoleConstants::ADMIN_ROLE_VALUE => 'from-blue-500 to-indigo-600',
            RoleConstants::CLUB_ADMINISTRATOR_VALUE => 'from-green-500 to-emerald-600',
            RoleConstants::LICENSING_DEPARTMENT_VALUE => 'from-indigo-500 to-purple-600',
            RoleConstants::LEGAL_DEPARTMENT_VALUE => 'from-purple-500 to-pink-600',
            RoleConstants::FINANCE_DEPARTMENT_VALUE => 'from-yellow-500 to-orange-600',
            RoleConstants::INFRASTRUCTURE_DEPARTMENT_VALUE => 'from-cyan-500 to-blue-600',
            RoleConstants::CONTROL_DEPARTMENT_VALUE => 'from-red-500 to-pink-600',
            RoleConstants::LEGAL_SPECIALIST_VALUE => 'from-purple-400 to-indigo-500',
            RoleConstants::FINANCIAL_SPECIALIST_VALUE => 'from-orange-400 to-yellow-500',
            RoleConstants::SPORTING_DIRECTOR_VALUE => 'from-teal-500 to-cyan-600',
            default => 'from-gray-500 to-gray-600',
        };
    }
}
