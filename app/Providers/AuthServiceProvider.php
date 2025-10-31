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
        // Define gates dynamically from permissions in database
        try {
            $permissions = Permission::all();

            foreach ($permissions as $permission) {
                Gate::define($permission->value, function ($user) use ($permission) {
                    // Check if user's role has this permission
                    return $user->role && $user->role->permissions->contains('id', $permission->id);
                });
            }
        } catch (\Exception $e) {
            // Catch exception during migrations when permissions table doesn't exist yet
        }
    }
}
