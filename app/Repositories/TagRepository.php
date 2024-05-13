<?php
namespace App\Repositories;

use App\Model\User;
use Illuminate\Support\Facades\Redis;
use App\Repositories\RedisRepository;
use App\Model\Tag;
use DB;
class TagRepository{
	protected $redisRepository;
    function __construct(){
        $this->redisRepository = new RedisRepository;
    }
    function create($input){
        if(Tag::create($input)){
            $this->redisRepository->getListTag('update');
            return true;
        }
        return false;
    }
    function update($tag_id, $input){
        if(Tag::whereId($tag_id)->update($input))
        {
            $this->redisRepository->getListTag('update');
            return true;
        }
        return false;
    }
    function destroy($tag_id, $option){
    	if(Tag::whereId($tag_id)->update(['status' => config('constant.status.off')]))
    	{
            if($option == 'force') return Tag::findOrFail($tag_id)->delete();
            $this->redisRepository->getListTag('update');
            return true;
        }
        return false;
        
    }
    function restore($tag_id){
        $restore = Tag::whereId($tag_id)->update(['status' => config('constant.status.on')]);
        $this->redisRepository->getListTag('update');
        return $restore;
    }

	function getAllWithRequest($request){
		$query = DB::table('tags')->select(['id', 'name', 'status', 'updated_at']);
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
        return DB::table('tags')->whereStatus(config('constant.status.on'))->get()->keyBy('id')->toArray();
    }
    function getRootCategory(){
        return DB::table('tags')->where('parent_id',0)->get();
    }
    function getbyID($user_id){
        return DB::table('tags')->whereId($user_id)->first();
    }
    function getbyAttribute($atr, $value){
        return DB::table('tags')->where($atr, $value);
    }

    function blogNewsPerTag($tag_id){
    	return DB::table('blog_news')->select('id')
    	    ->whereStatus(config('constant.status.on'))->whereRaw('FIND_IN_SET('.$tag_id.',tags)')->get();
    }
}