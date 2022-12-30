<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {

        $roleJson = $user->group->permissions;
        if (!empty($roleJson)) {
            $roleArr = json_decode($roleJson, true);

            $check = isRole($roleArr, 'users');
            return $check;
        }

        return false;
        // return $user->checkPermissionAccess(config('permissions.access.list-user'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {

    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $roleJson = $user->group->permissions;
        if (!empty($roleJson)) {
            $roleArr = json_decode($roleJson, true);

            $check = isRole($roleArr, 'users', 'add');
            return $check;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    // public function update(User $user)
    // {
    //     $roleJson = $user->group->permissions;
    //     if (!empty($roleJson)) {
    //         $roleArr = json_decode($roleJson, true);

    //         $check = isRole($roleArr, 'users', 'edit');
    //         return $check;
    //     }

    //     return false;
    // }

    public function update(User $user, User $model)
    {
        // dd($user->id == $model->user_id);
        $roleJson = $user->group->permissions;
        if (!empty($roleJson)) {
            $roleArr = json_decode($roleJson, true);

            $check = isRole($roleArr, 'users', 'edit');
            return $check;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
