<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name', 'slug','description','cover','status','metakey','metades','metarobot'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'brand_product');
    }


    public function scopeActive($query){
        return $query->where('status',config('constant.status.on'));
    }
}
