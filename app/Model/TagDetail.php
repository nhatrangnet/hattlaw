<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TagDetail extends Model
{
    protected $table = 'tag_detail';
    public $timestamps = false;
    protected $primarykey = 'tag_id';
    protected $fillable = ['tag_id','news_id','product_id'];
}
