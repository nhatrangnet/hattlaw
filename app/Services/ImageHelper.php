<?php
namespace App\Services;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Repositories\RedisRepository;
use DB, File;
class ImageHelper
{
	/**
	 * Function description
	 * @return true
	 */
	public function upload_image($image, $path, $newname, $thumb = 'yes', $watermark = 'no')
	{
		try {

	    	list($width, $height) = getimagesize($image);
			if($width > config('constant.max_image_width')){
				$upload_image = Image::make($image)->resize(config('constant.image.large'),null, function($cons){
		            $cons->aspectRatio();
		        });
			}
			else $upload_image = Image::make($image);
			$final_path = $this->check_or_create_path(public_path('storage').$path);
			if($watermark == 'yes'){
				$upload_image->insert(public_path('storage/watermark.png'),'bottom-right',2,2);
			}
			$this->removeOldImage($final_path, $newname);
			$upload_image->save($final_path.'/'.$newname);

			//make thumnail
			if($thumb == 'yes'){
				$final_path = $this->check_or_create_path(public_path('storage').$path.'/thumb/');

				$thumb_small = Image::make($image)->resize(config('constant.image.small'),null, function($constraint){
		            $constraint->aspectRatio();
		            $constraint->upsize();
		        });
				$thumb_small->save($final_path.'small_'.$newname);

				$thumb_medium = Image::make($image)->resize(config('constant.image.medium'),null, function($constraint){
		            $constraint->aspectRatio();
		            $constraint->upsize();
		        });
				$thumb_medium->save($final_path.'medium_'.$newname);
			}
			return true;
		}
		catch (Exception $e) {
			return false;
		}

		
	}


	/**
	 * Function description
	 * @return true
	 */
	public function removeOldImage($path, $name, $month_year = false)
	{
		if(!empty($month_year)) $final_path = public_path('storage').$path.'/'.$month_year.'/'.$name;
		else $final_path = public_path('storage').$path.'/'.$name;
		if($this->check_file_exists($final_path)){
			if(!empty($month_year)){
				$thumb_small = public_path('storage').$path.'/'.$month_year.'/thumb/'.'small_'.$name;
				$thumb_medium = public_path('storage').$path.'/'.$month_year.'/thumb/'.'medium_'.$name;
			}
			else{
				$thumb_small = public_path('storage').$path.'/thumb/'.'small_'.$name;
				$thumb_medium = public_path('storage').$path.'/thumb/'.'medium_'.$name;
			}
			File::delete($final_path);
			File::delete($thumb_small);
			File::delete($thumb_medium);
		}
	    return true;
	}

	/**
	 * Function description
	 * @return true
	 */
	public function check_file_exists($path_file)
	{
	    return \file_exists($path_file);
	}
	/**
	 * Function description
	 * @return true
	 */
	public function check_or_create_path($path)
	{
	    if(!file_exists($path)){
            \File::makeDirectory($path, intval( config('constant.permissions.folder'), 8 ), true);
        }
	    return $path;
	}
}