<?php
namespace App\Repositories;

use App\Model\User;
use Illuminate\Support\Facades\Redis;
use App\Repositories\RedisRepository;
use App\Model\GalleryCategory;
use App\Model\GalleryImage;
use DB;
class GalleryRepository extends Repositories{
	protected $redisRepository;
    function __construct(){
        $this->redisRepository = new RedisRepository;
    }
    function create($input){
        if(GalleryCategory::create($input)){
            return true;
        }
        return false;
    }
    function update($category_id, $input){
        if(GalleryCategory::whereId($category_id)->update($input))
        {
            return true;
        }
        return false;
    }
    /**
     * get_list_gallery_category
     * @return true
     */
    function get_list_gallery_category()
    {
        $categories = $this->getbyTableField('gallery_category', ['status' => config('constant.status.on')], ['id', 'name','slug']);
        $category_list=[];
        foreach($categories as $cat){
            $category_list[$cat->id]['name'] = $cat->name;
            $image_list = $this->getbyTableField('gallery_image', ['orderby' => 'image_DESC', 'status' => config('constant.status.on'), 'gallery_category_id' => $cat->id], ['image','description']);
            if($image_list->count() > 0){
                foreach($image_list as $key => $image){
                    $category_list[$cat->id]['image_list'][$key]['image'] = $image->image;
                    $category_list[$cat->id]['image_list'][$key]['description'] = $image->description;
                }
            }
        }
        return $category_list;
    }

    function destroy($category_id, $option){
    	if(GalleryCategory::whereId($category_id)->update(['status' => config('constant.status.off')]))
    	{
            if($option == 'force') return GalleryCategory::findOrFail($category_id)->delete();
            $this->redisRepository->getListTag('update');
            return true;
        }
        return false;
        
    }
    function restore($category_id){
        $restore = GalleryCategory::whereId($category_id)->update(['status' => config('constant.status.on')]);
        $this->redisRepository->getListTag('update');
        return $restore;
    }

    function getAllWithRequest($request){
      $query = DB::table('gallery_category')->select(['id', 'name', 'status', 'updated_at']);
      $date_search = getDateTimeToSearch($request->get('from_datetime'), $request->get('to_datetime'));
      if ($request->has('name') && $request->get('name') != '') {
        $query->where('name', 'like', "%{$request->get('name')}%");
    }
    if ($request->has('from_datetime') && $request->get('from_datetime') != '') {
     $query->where('updated_at', '>=', $date_search['from']);
 }

 if ($request->has('to_datetime') && $request->get('to_datetime') != '') {
     $query->where('updated_at', '<=', $date_search['to']);
 }


 return $query->get();
}
function getAlActive(){
    return DB::table('gallery_category')->whereStatus(config('constant.status.on'))->get()->keyBy('id')->toArray();
}

}