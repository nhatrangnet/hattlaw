<?php

namespace App\Policies;

use App\Model\Admin;
use App\Model\BlogNews;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogNewsPolicy
{
    use HandlesAuthorization;

    public function before($admin)
    {
        if ($admin->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the admin.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Admin  $admin
     * @return mixed
     */
    public function view(Admin $admin)
    {
        return $admin->hasAccess(['blog_news_view']);
    }

    /**
     * Function description
     * @return true
     */
    public function edit()
    {

        return 'true';
    }

    /**
     * Determine whether the user can create admins.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(Admin $admin)
    {
        return $admin->hasAccess(['blog_news_create']);
    }

    /**
     * Determine whether the user can update the admin.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Admin  $admin
     * @return mixed
     */
    public function update(Admin $admin, BlogNews $post)
    {
        // return $admin->hasAccess(['news_update']) or $admin->id == $post->admin_id;
        return true;
    }

    /**
     * Determine whether the user can delete the admin.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Admin  $admin
     * @return mixed
     */
    public function delete(Admin $admin, BlogNews $post)
    {
        return $admin->hasAccess(['news_delete']) or $admin->id == $post->admin_id;
    }

    /**
     * Determine whether the user can restore the admin.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Admin  $admin
     * @return mixed
     */
    public function restore(Admin $admin)
    {
        return $admin->hasAccess(['news_restore']) or $admin->id == $post->admin_id;
    }

    /**
     * Determine whether the user can permanently delete the admin.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Admin  $admin
     * @return mixed
     */
    public function forceDelete(Admin $admin)
    {
        //
    }
}
