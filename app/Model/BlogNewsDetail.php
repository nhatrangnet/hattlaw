<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BlogNewsDetail extends Model
{
    protected $table = 'blog_news_detail';
    public $timestamps = false;
    protected $primarykey = 'blog_news_id';
    protected $fillable = ['blog_news_id','blog_category_id'];
}
