<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_status';
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $fillable = ['id','name','description'];
}
