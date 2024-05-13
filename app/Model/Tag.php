<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name','admin_id','slug','status'];
    
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
