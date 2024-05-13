<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogNews extends Model
{
    use SoftDeletes;
    
    protected $guarded = ['hit'];

    public static function boot(){
        parent::boot();
    }

    /**
     * Function description
     * @return true
     */
    public function category()
    {
        return $this->belongsToMany(BlogCategory::class);
    }
}
