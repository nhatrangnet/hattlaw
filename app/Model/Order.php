<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Product;
class Order extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'deliver_name', 'deliver_address','deliver_phone','deliver_email','note','subtotal','discount_percent','coupon_id','shipping_charge','total','status'
    ];


    /**
     * products
     * @return true
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product');
    }

    public function scopeActive($query){
        return $query->where('status',config('constant.status.on'));
    }
}
