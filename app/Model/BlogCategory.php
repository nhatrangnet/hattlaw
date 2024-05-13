<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = ['name','slug','description','cover','status','parent_id', 'metakey','metarobot'];

    public static function boot(){
        parent::boot();
    }

    /**
     * Function description
     * @return true
     */
    public function news()
    {
        return $this->belongsToMany(BlogNews::class);
    }
}
