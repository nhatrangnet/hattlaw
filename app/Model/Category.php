<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Product;

class Category extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug','description','cover','status','metakey','metades','metarobot','hit'
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    public function scopeActive($query){
        return $query->where('status',config('constant.status.on'));
    }
}
