<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\User;

class Role extends Model
{
    use SoftDeletes;
	protected $fillable = [
        'name', 'slug', 'permissions','status'
    ];
    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Function description
     * @return true
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_admin');
    }

    public function hasAccessRole(array $permissions) : bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission))
                return true;
        }
        return false;
    }

    private function hasPermission(string $permission) : bool
    {
        return $this->permissions[$permission] ?? false;
    }
}
