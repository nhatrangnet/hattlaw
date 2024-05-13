<?php
namespace App\Repositories;
use App\Repositories\Repositories;
use App\Model\User;
use App\Model\Admin;
use App\Model\Config;
use DB;
use Illuminate\Support\Facades\Redis;
use App\Repositories\Blog\CategoryRepository as BlogCategoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\RoleRepository;


class RedisRepository extends Repositories{
	/**
     * get full blog categories parent-child
     * @return true
     */
    public function getListBlogCategory($command = null, $expire = null, $getId = false)
    {
    	if($command != 'update'){
    		$blog_categories = json_decode(get_key(redis_key('blog_categories')), true);
        	if(!empty($blog_categories) && count($blog_categories) > 0) return $blog_categories;
    	}
    	$data=[];

        $blogCategoryRepository = new BlogCategoryRepository;
        $allCategoriesActive = $blogCategoryRepository->getAlActive();
        foreach($allCategoriesActive as $id => $cat){
        	if($cat->parent_id == 0){
                $data[$cat->slug]['id'] = $cat->id;
                $data[$cat->slug]['name'] = $cat->name;
                $data[$cat->slug]['metakey'] = $cat->metakey;
                $data[$cat->slug]['description'] = $cat->description;
                $data[$cat->slug]['metarobot'] = $cat->metarobot;
                $data[$cat->slug]['cover'] = $cat->cover;
            }
        	else{
        		$data[$allCategoriesActive[$cat->parent_id]->slug]['sub_id'][] = $cat->id;
                $data[$allCategoriesActive[$cat->parent_id]->slug]['sub_id_name'][$cat->id] = $cat->name;


                $data[$allCategoriesActive[$cat->parent_id]->slug]['sub'][$cat->slug]['id'] = $cat->id;   // 
        		$data[$allCategoriesActive[$cat->parent_id]->slug]['sub'][$cat->slug]['name'] = $cat->name;
                $data[$allCategoriesActive[$cat->parent_id]->slug]['sub'][$cat->slug]['metakey'] = $cat->metakey;
                $data[$allCategoriesActive[$cat->parent_id]->slug]['sub'][$cat->slug]['description'] = $cat->description;
                $data[$allCategoriesActive[$cat->parent_id]->slug]['sub'][$cat->slug]['metarobot'] = $cat->metarobot;
                $data[$allCategoriesActive[$cat->parent_id]->slug]['sub'][$cat->slug]['cover'] = $cat->cover;
        	}
        }

        foreach($data as $slug => $category){
            if(!empty($category['sub'])){
                foreach($category['sub'] as $sub_slug => $sub){
                    $news_detail = $this->getbyTableField('blog_news_detail', ['blog_category_id' => $sub['id']], 'blog_news_id');
                    foreach($news_detail as $news){
                        $data[$slug]['sub'][$sub_slug]['news'][$news->blog_news_id] = $news->blog_news_id;
                    }
                }
            }
            $news_detail=[];
            $search_id[] = $category['id'];

            if(!empty($category['sub_id'])){
                $category['sub_id'][] = $category['id'];
                $search_id = $category['sub_id'];
            }

            $news_detail = $this->getbyTableField('blog_news_detail', ['blog_category_id' => $search_id, 'blog_news_status' => config('constant.status.on')], 'blog_news_id');

            foreach($news_detail as $news){
                $data[$slug]['news'][$news->blog_news_id] = $news->blog_news_id;
            }
            $search_id = [];
        }
        if($expire == null) $expire = (int)config('constant.redis_expire_day')*3600;
        set_key(redis_key('blog_categories'), json_encode($data), $expire);
        return $data;
    }

    function getListProductCategory($command = null, $expire = null)
    {
        if($command != 'update'){
            $product_categories = json_decode(get_key(redis_key('product_categories')), true);
            if(!empty($product_categories) && count($product_categories) > 0) return $product_categories;
        }

        $rootCategory = $this->getRootProductCategory();
        $return =[];
        foreach($rootCategory as $cat){
            $subCategory = $this->getSubProductCategory($cat->id);
            if(count($subCategory) > 0){
                $return[$cat->id]['name'] = $cat->name;
                foreach($subCategory as $key => $sub){
                    $return[$cat->id]['sub'][$sub->id]['name'] = $sub->name;
                    $return[$cat->id]['sub'][$sub->id]['slug'] = $sub->slug;
                    $return[$cat->id]['sub'][$sub->id]['cover'] = $sub->cover;
                    $return[$cat->id]['sub'][$sub->id]['hit'] = $sub->hit;
                }
                
            }
            else $return[$cat->id] = $cat->name;
        }
        if($expire == null) $expire = (int)config('constant.redis_expire_day')*3600;
        set_key(redis_key('product_categories'), json_encode($return), $expire);
        return $return;
    }
    function getRootProductCategory(){
        return DB::table('categories')->select('id','parent_id','admin_id','name','slug','cover','status','hit')->where('status', config('constant.status.on'))->where('parent_id',0)->get();
    }
    function getSubProductCategory($parent_id){
        return DB::table('categories')->select('id','parent_id','admin_id','name','slug','cover','status','hit')->where('status', config('constant.status.on'))->where('parent_id',$parent_id)->get();
    }

    function getListTag($command = null, $expire = null){
        if($command != 'update'){
            $list_tag = json_decode(get_key(redis_key('list_tag')), true);
            if(!empty($list_tag) && count($list_tag) > 0) return $list_tag;
        }

        $tagRepository = new TagRepository;
        $listTagAactive = $tagRepository->getAlActive();
        $data=[];
        foreach($listTagAactive as $tag){
            $data[$tag->id] = $tag->name;
        }
        
        if($expire == null) $expire = 3600*(int)config('constant.redis_expire_day');
        set_key(redis_key('list_tag'), json_encode($data), $expire);
        return $data;
    }

    function getListRole($command = null, $expire = null){
        // if($command != 'update'){
        //     $list_tag = json_decode(get_key(redis_key('list_role')), true);
        //     if(!empty($list_tag) && count($list_tag) > 0) return $list_tag;
        // }

        $roleRepository = new RoleRepository;
        $listRoleAactive = $roleRepository->getAlActive();
        $data=[];
        foreach($listRoleAactive as $role){
            $data[$role->id] = $role->name;
        }
        
        if($expire == null) $expire = 3600*(int)config('constant.redis_expire_day');
        set_key(redis_key('list_role'), json_encode($data), $expire);
        return $data;
    }
    function getListRoleId($command = null, $expire = null){
        if($command != 'update'){
            $list_tag = json_decode(get_key(redis_key('list_role')), true);
            if(!empty($list_tag) && count($list_tag) > 0) return $list_tag;
        }

        $roleRepository = new RoleRepository;
        $listRoleAactive = $roleRepository->getAlActive();
        $data=[];
        foreach($listRoleAactive as $role){
            $data[$role->id] = $role->name;
        }
        
        if($expire == null) $expire = 3600*(int)config('constant.redis_expire_day');
        set_key(redis_key('list_role'), json_encode($data), $expire);
        return $data;
    }
    /**
     * getConfig
     * @return true
     */
    public function getConfig($role = 'config')
    {
        $config = get_key(redis_key('config'));
        if(!empty($config['metakey']) && $role == 'config') return $config;
        else{
            $data_db = DB::table('configs')->where('role', $role)->first();
            if(!empty($data_db->value)){
                if($role == 'config') set_key(redis_key('config'), $data_db->value, 3600*(int)config('constant.redis_expire_day'));
                return $data_db->value;
            }
            return false;
        }
    }
    /**
     * saveConfig
     * @return true
     */
    public function saveConfig($input, $role='config')
    {
        try
        {
            DB::table('configs')->updateOrInsert(
                ['role' => $input['role']],
                ['value' => json_encode($input['value'])]
            );
            if($role  == 'config') set_key(redis_key('config'), json_encode($input['value']), 3600*(int)config('constant.redis_expire_day'));
            return true;
        }
        catch (\Exception $e){
            return false;
        }
    }
}
