<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug','description','metakey', 'metarobot','metades','en_metakey', 'en_metarobot','en_metades','en_name', 'en_slug','en_description','hit','status'
    ];


    public function scopeActive($query){
        return $query->where('status',config('constant.status.on'));
    }
}
