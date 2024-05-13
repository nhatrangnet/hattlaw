<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
	protected $table = 'gallery_image';
    protected $fillable = [
        'gallery_category_id','name', 'image','description', 'status'
    ];
}
