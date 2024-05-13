<?php
namespace App\Repositories\Blog;

use App\Repositories\Repositories;
use App\Model\BlogNews;
use Illuminate\Support\Facades\Redis;
use Yajra\Datatables\Datatables;
use App\Repositories\RedisRepository;
use DB;
class NewsRepository extends Repositories{
    protected $redisRepository;
    function __construct(){
        $this->redisRepository = new RedisRepository;
    }
    function create($input){
        try{
            if(!empty($input['category_id'])){
                $category_id = $input['category_id'];
                unset($input['category_id']);
            }
            if(!empty($input['tag_id'])){
                $category_id = $input['tag_id'];
                unset($input['tag_id']);
            }
            $blog_news = BlogNews::create($input);
            if(!empty($category_id)){

                foreach($category_id as $cat){
                    DB::table('blog_news_detail')->insert([
                        'blog_news_id' => $blog_news->id,
                        'blog_category_id' => $cat,
                        'blog_news_status' => $input['status']
                    ]);
                }
            }
            if(!empty($tag_id)){
                foreach($tag_id as $tag){
                    DB::table('tag_detail')->insert([
                        'blog_news_id' => $blog_news->id,
                        'tag_id' => $tag,
                        'blog_news_status' => $input['status']
                    ]);
                }
            }
            $this->redisRepository->getListBlogCategory('update');
            return true;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }
        
    }
    function update($news_id, $input){
        try{
            if(!empty($input['category_id'])){
                $category_id = $input['category_id'];
                unset($input['category_id']);
            }
            if(!empty($input['tag_id'])){
                $tag_id = $input['tag_id'];
                unset($input['tag_id']);
            }

            $this->deleteTableWhereIn('blog_news_detail', 'blog_news_id',[$news_id]);            
            $this->deleteTableWhereIn('tag_detail', 'blog_news_id',[$news_id]);

            BlogNews::whereId($news_id)->update($input);

            if(!empty($category_id)){
                foreach($category_id as $cat){
                    $update_news_detail[] = [
                        'blog_news_id' => $news_id,
                        'blog_category_id' => $cat,
                        'blog_news_status' => $input['status']
                    ];
                }
                DB::table('blog_news_detail')->insert($update_news_detail);
                // $this->insertTableValue('blog_news_detail', $update_news_detail);
            }

            

            if(!empty($tag_id)){
                foreach($tag_id as $tag){
                    $update_tag_detail[] = [
                        'blog_news_id' => $news_id,
                        'tag_id' => $tag,
                        'blog_news_status' => $input['status']
                    ];
                }
                DB::table('tag_detail')->insert($update_news_detail);
                // $this->insertTableValue('tag_detail', $update_tag_detail);
            }
            
            $this->redisRepository->getListBlogCategory('update');
            return true;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }
    }
    function destroy($news_id){
        return BlogNews::findOrFail($news_id)->delete();
    }
    function restore($news_id){
        try{
            $admin = BlogNews::onlyTrashed()->whereId($news_id);
            $admin->restore();

            $admin = BlogNews::findOrFail($news_id);
            $admin->status = config('constant.status.on');
            return $admin->save();
            
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

	function getAllWithRequestEloquent($request){
		$query = BlogNews::select('*')->withTrashed();
        $date_search = getDateTimeToSearch($request->get('from_datetime'), $request->get('to_datetime'));
		if ($request->has('title') && $request->get('title') != '') {
            $query->where('title', 'like', "%{$request->get('title')}%");
        }
        if ($request->has('status') && $request->get('status') != '') {
            $query->where('status', $request->get('status'));
        }

        if ($request->has('from_datetime') && $request->get('from_datetime') != '') {
           $query->where('updated_at', '>=', $date_search['from']);
        }

        if ($request->has('to_datetime') && $request->get('to_datetime') != '') {
           $query->where('updated_at', '<=', $date_search['to']);
        }

        if ($request->has('search_categories') && $request->get('search_categories') != '') {
            $query->whereIn('category_id', $request->get('search_categories'));
        }

        if ($request->has('search_category') && $request->get('search_category') != '') {
            $query->where('category_id', $request->get('search_category'));
        }

		return $query;
	}
    function getAllActive(){
    	// return User::active()->get(); //use scope
        return DB::table('blog_news')->where('status',config('constant.status.on'))->get();
    }
}
