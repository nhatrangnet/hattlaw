<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Role;
use DB;


class Config extends Model
{
    use SoftDeletes;

    protected $fillable = ['role','value'];
}
