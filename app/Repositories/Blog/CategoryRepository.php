<?php
namespace App\Repositories\Blog;

use App\Repositories\Repositories as Repositories;
use App\Model\BlogCategory;
use Illuminate\Support\Facades\Redis;
use Yajra\Datatables\Datatables;
use App\Repositories\RedisRepository;
use DB;
class CategoryRepository extends Repositories{
    protected $redisRepository;
    function __construct(){
        parent::__construct();
        $this->redisRepository = new RedisRepository;
    }
    function create($input){
        if(BlogCategory::create($input)){
            $this->redisRepository->getListBlogCategory('update');
            return true;
        }
        return false;
    }
    function update($category_id, $input){
        if(BlogCategory::whereId($category_id)->update($input)){
            $this->redisRepository->getListBlogCategory('update');
            return true;
        }
        return false;
    }
    function destroy($category_id, $option){
        try
        {
            $blog_category = BlogCategory::findOrFail($category_id);
            $blog_category->status = config('constant.status.off');

            if($option == 'force'){
                //delete cover
                if($blog_category->cover != NULL )$this->imageService->removeOldImage(config('constant.image.blogcat'), $blog_category->cover);
                $return = BlogCategory::findOrFail($category_id)->delete();
            
                //update blog news parent_id=0
                $this->deleteTableWhereIn('blog_news_detail', 'blog_category_id', [$category_id]);
            }
            else $return = $blog_category->save();
            $this->redisRepository->getListBlogCategory('update');

            return $return;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }
    }
    function restore($category_id){
        $restore = BlogCategory::whereId($category_id)->update(['status' => config('constant.status.on')]);
        $this->redisRepository->getListBlogCategory('update');
        return $restore;
    }


	function getAllWithRequest($request){
		$query = DB::table('blog_categories')->select(['id', 'name', 'status', 'updated_at']);
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
        

		return $query;
	}
    function getAlActive(){
    	// return User::active()->get(); //use scope
        return DB::table('blog_categories')->whereStatus(config('constant.status.on'))->get()->keyBy('id')->toArray();
    }
    function getRootCategory(){
        return DB::table('blog_categories')->where('status', config('constant.status.on'))->where('parent_id',0)->get();
    }

    function getChildCategory($id){
        return DB::table('blog_categories')->where('status', config('constant.status.on'))->where('parent_id',$id)->get();
    }
}
