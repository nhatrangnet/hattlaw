<?php
namespace App\Repositories;

use App\Model\User;
use Illuminate\Support\Facades\Redis;
use App\Repositories\RedisRepository;
use App\Model\Review;
use DB;
class ReviewRepository{
	protected $redisRepository;
    function __construct(){
        $this->redisRepository = new RedisRepository;
    }
    function create($input){
        if(Review::create($input)) return true;
        return false;
    }
    function update($review_id, $input){
        if(Review::whereId($review_id)->update($input)) return true;
        return false;
    }
    function destroy($review_id, $option){
    	if(Review::whereId($review_id)->update(['status' => config('constant.status.off')]))
    	{
            if($option == 'force') return Review::findOrFail($review_id)->delete();
            $this->redisRepository->getListTag('update');
            return true;
        }
        return false;
        
    }
    function restore($review_id){
        $restore = Review::whereId($review_id)->update(['status' => config('constant.status.on')]);
        $this->redisRepository->getListTag('update');
        return $restore;
    }

	function getAllWithRequest($request){
		$query = DB::table('reviews')->select(['*']);
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
        return DB::table('reviews')->whereStatus(config('constant.status.on'))->get()->keyBy('id')->toArray();
    }
    function getRootCategory(){
        return DB::table('reviews')->where('parent_id',0)->get();
    }
    function getbyID($user_id){
        return DB::table('reviews')->whereId($user_id)->first();
    }
    function getbyAttribute($atr, $value){
        return DB::table('reviews')->where($atr, $value);
    }

    function blogNewsPerTag($review_id){
    	return DB::table('blog_news')->select('id')
    	    ->whereStatus(config('constant.status.on'))->whereRaw('FIND_IN_SET('.$review_id.',reviews)')->get();
    }
}