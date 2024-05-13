<?php

namespace App\Policies;
use App\Model\Admin;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    public function before($admin)
    {
        if ($admin->isSuperAdmin()) {            
            return true;
        }
    }

    /**
     * Determine whether the user can view the model user.
     *
     * @param  \App\Model\User  $user
     * @param  \App\ModelUser  $modelUser
     * @return mixed
     */
    public function view(Admin $admin)
    {
        // echo '<pre>';print_r($admin);echo '</pre>';die;
        return $admin->hasAccess(['user_view']);
    }

    /**
     * Function description
     * @return true
     */
    public function edit()
    {
        return true;
    }

    /**
     * Determine whether the user can create model users.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model user.
     *
     * @param  \App\Model\User  $user
     * @param  \App\ModelUser  $modelUser
     * @return mixed
     */
    public function update(User $user)
    {
        //
    }

    /**
     * Determine whether the user can delete the model user.
     *
     * @param  \App\Model\User  $user
     * @param  \App\ModelUser  $modelUser
     * @return mixed
     */
    public function delete(Admin $admin)
    {
        return $admin->hasAccess(['user_soft_delete']);
    }

    /**
     * Determine whether the user can restore the model user.
     *
     * @param  \App\Model\User  $user
     * @param  \App\ModelUser  $modelUser
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model user.
     *
     * @param  \App\Model\User  $user
     * @param  \App\ModelUser  $modelUser
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
