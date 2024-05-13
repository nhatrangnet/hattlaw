<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    // use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_name', 'author_url','profile_photo','rating','text','status'
    ];

    public static function boot(){
        parent::boot();
    }

    public function scopeActive($query){
        return $query->where('status',config('constant.status.on'));
    }
}
