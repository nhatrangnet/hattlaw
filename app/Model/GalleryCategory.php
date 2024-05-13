<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GalleryCategory extends Model
{
	protected $table = 'gallery_category';
    protected $fillable = [
        'name', 'slug', 'status', 'admin_id','cover'
    ];
}
