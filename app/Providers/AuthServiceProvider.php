<?php

namespace App\Providers;

use App\Models\Module;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate::define('list_user', function ($user) {
        //     return $user->checkPermissionAccess(config('permissions.access.list-user'));
        // });

        // Gate::define('add_user', function ($user) {
        //     return $user->checkPermissionAccess('add_user');
        // });

        // Gate::define('edit_user', function ($user, $id) {
        //     $users = User::find($id);
        //     if( $user->checkPermissionAccess('edit_user') && $user->id == $users->user_id){
        //         return true;
        //     }
        //     return false;
        // });

        // Gate::define('list_user','App\Policies\UserPolicy@viewAny');

        $modulesList = Module::all();
        if ($modulesList->count() > 0) {
            foreach ($modulesList as $module) {
                Gate::define($module->title, function (User $user) use ($module) {
                    $roleJson = $user->group->permissions;

                    if (!empty($roleJson)) {
                        $roleArr = json_decode($roleJson, true);
                        $check = isRole($roleArr, $module->title);
                        return $check;
                    }

                    return false;
                });

                Gate::define($module->title.'.edit', function (User $user) use ($module) {
                    $roleJson = $user->group->permissions;

                    if (!empty($roleJson)) {
                        $roleArr = json_decode($roleJson, true);
                        $check = isRole($roleArr, $module->title, 'edit');
                        return $check;
                    }

                    return false;
                });
            }
        }
    }
}
