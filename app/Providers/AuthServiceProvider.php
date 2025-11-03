<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates dynamically from permissions in database
        try {
            // Only load permissions if the table exists
            if (\Schema::hasTable('permissions') && \Schema::hasTable('role_permission')) {
                $permissions = Permission::all();

                foreach ($permissions as $permission) {
                    Gate::define($permission->value, function ($user) use ($permission) {
                        // Check if user's role has this permission
                        return $user->role && $user->role->permissions->contains('id', $permission->id);
                    });
                }
            }
        } catch (\Exception $e) {
            // Catch exception during migrations when tables don't exist yet
            \Log::error('Error loading permissions: ' . $e->getMessage());
        }
    }
}
