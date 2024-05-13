<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Role;
use DB;


class Admin extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = ['name','en_name','email','password','slug','description','en_description','avatar','address','phone'];
    protected $hidden = ['password','remember_token'];

    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->password = Hash::make($model->password);
        });
    }

    /**
     * roles
     * @return true
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_admin');
    }

    /**
     * Checks if User has access to $permissions.
     * @return true
     */
    public function hasAccess(array $permissions)
    {
    	foreach ($this->roles as $role) {

            if($role->hasAccessRole($permissions)) {
                return true;
            }
        }
        return false;
    }
    /**
     * Checks if the user belongs to role.
     */
    // public function inRole(string $roleSlug)
    // {
    //     return $this->roles()->where('slug', $roleSlug)->count() == 1;
    // }
    /**
     * check admin logged in is SUPER
     * @return true
     */
    public function isSuperAdmin()
    {
        foreach ($this->roles as $role) {
            if($role->slug == 'super_admin') {
                return true;
            }
        }
        return false;
    }
    public static function name($id)
    {
        $query = Admin::select('name')->where('id', $id)->first();
        return $query['name'];
    }
}
