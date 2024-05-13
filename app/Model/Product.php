<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Catogory;
use App\Model\Brand;
use App\Model\Cart;

class Product extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admin_id','name', 'slug','sku','description','cover','quantity','price','status','metakey','metades','metarobot','hit'
    ];


    /**
     * categories
     * @return true
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    /**
     * brands
     * @return true
     */
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_product');
    }

    /**
     * orders
     * @return true
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product');
    }
    
    public function images()
    {
        
    }

    public function scopeActive($query){
        return $query->where('status',config('constant.status.on'));
    }
}
