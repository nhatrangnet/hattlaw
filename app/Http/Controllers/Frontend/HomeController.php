<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController as FrontendController;
use Modules\Admin\Http\Requests\RegisterAdminRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use App\Repositories\GalleryRepository;
use App\Repositories\Blog\CategoryRepository as BlogCategoryRepository;
use App\Repositories\Blog\NewsRepository as BlogNewsRepository;
use App\Repositories\ReviewRepository as ReviewRepository;
use App\Repositories\ServiceRepository as ServiceRepository;
use App\Repositories\AdminRepository;
use Intervention\Image\ImageManagerStatic as Image;
use App\Model\Admin;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use URL, Input, Validator, HTML, Session, DB, Theme, File;

class HomeController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(BlogNewsRepository $blogNewsRepository, ServiceRepository $serviceRepo)
    {
        $this->theme->set('user', $this->user_logged);
        $this->theme->asset()->serve('swiper');
        $this->theme->asset()->serve('aos');

        $data['slogan'] = trans('frontend.slogan');

        $data['slide'] = [];
        $data['list_news'] = $this->repository->get_data_paginate('blog_news', ['status' => config('constant.status.on') ], 3);
        $config = json_decode(get_key(redis_key('config')), true);
        if(!empty($config['defaultslide'])){
            $defaultslide = explode(',',$config['defaultslide']);
            foreach($defaultslide as $key => $slide){
                $data['slide'][$key]['image_slide'] = url('storage'.config('constant.image.default').'/'.$slide);
                $data['slide'][$key]['slug'] = $slide;
            }
        }
        if(!empty($data['list_news'])){
            foreach($data['list_news'] as $news){
                if(!empty($news->image_slide)){
                    $data['slide'][$news->slug]['image_slide'] = url('storage'.config('constant.image.blognews').'/'.$news->image_slide);
                    $data['slide'][$news->slug]['slug'] = $news->slug;
                    $data['slide'][$news->slug]['title'] = $news->title;
                }
            }
        }
        $galleryRepo = new GalleryRepository;
        $data['category_list'] = $galleryRepo->get_list_gallery_category();

        $customer_list = $this->repository->getAllTableActive(config('constant.database.user'), ['id','name','website','avatar']);
        $data['customer_list'] = [];
        if(!empty($customer_list)){
            foreach($customer_list as $customer){
                $data['customer_list'][$customer->id]['name'] = $customer->name;
                $data['customer_list'][$customer->id]['website'] = $customer->website;
                $data['customer_list'][$customer->id]['avatar'] = getImageLink(config('constant.image.user'), $customer->avatar);
            }
        }

        $reviewRepo = new ReviewRepository;
        $data['customer_reviews'] = $reviewRepo->getAlActive();

        return $this->render('frontend.index', $data);
    }

    public function changeLanguage($language)
    {
        Session::put('hatlaw_language', $language);
        return redirect()->back();
    }

    public function get_list_birthday_user_ajax(Request $request)
    {
        $users = $this->repository->searchStatisticUser($request);
        return Datatables::of($users)
            ->addIndexColumn()
            ->editColumn('birthday', function($object){
                return date("d-m-Y", strtotime($object->birthday));
            })
            ->editColumn('status_data', function($object){

                return $object->status==1? '<div class="text-center"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button></div>':'<div class="text-center"><button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button></div>';
            })
            ->addColumn('action', function ($object) {
                $return = '<a href="'.route('admin.edit',$object->id).'" class="btn btn-xs btn-primary mr-2" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><i class="fa fa-edit"></i></a>';
                return $return;
            })
            ->rawColumns(['action','status_data'])
        ->make(true);

    }
    /**
     * Function description
     * @return true
     */
    public function introduce()
    {
        $introduce = $this->redisRepository->getConfig('introduce');
        $data['introduce'] = [];
        if(!empty($introduce)) $data['introduce'] = json_decode($introduce,true);
        return $this->render('frontend.introduce', $data);
    }
    public function our_team(AdminRepository $adminRepo)
    {
        $select = ['admins.slug','admins.name','admins.en_name','admins.avatar','admins.image','admins.description','admins.en_description','admins.metadata','admins.phone'];
        $data['nhanvien_list'] = $adminRepo->getAdminActiveByRole('nhanvien', $select);
        return $this->render('frontend.our_team', $data);
    }

    public function contact()
    {
        return $this->render('frontend.contact');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function userboard()
    {
        return view('frontend.userboard');
    }

    /**
     * Function description
     * @return true
     */
    public function gallery($category_id = false)
    {
        $this->theme->asset()->serve('lightbox');
        $galleryRepo = new GalleryRepository;
        // $this->theme->asset()->serve('jgallery');

        $data=[];
        $data['category_list'] = $galleryRepo->get_list_gallery_category();
        if(!empty($category_id)){
            $data['category'] = $category_id;
        }
        return $this->render('frontend.gallery', $data);
    }

    /**
     * Function description
     * @return true
     */
    public function category($category_slug, $page = null, BlogCategoryRepository $blogCategoryRepository, BlogNewsRepository $blogNewsRepository)
    {
        $this->theme->set('user', $this->user_logged);
        // $blogCategoryRepository->incrementValue('hit');

        $list_categories = $this->theme->bind('categories');

        if(isset($list_categories[$category_slug])) $current_category = $list_categories[$category_slug];
        else{
            foreach($list_categories as $cat){
                if(!empty($cat['sub'])){
                    foreach($cat['sub'] as $key => $sub){
                        if($key == $category_slug) $current_category = $sub;
                    }
                }
            }
        }
        // set theme
        $this->theme->breadcrumb()->add($current_category['name'], '');
        $this->theme->set('keywords',$current_category['metakey']??config('constant.meta.default.keywords'));
        $this->theme->set('description',$current_category['description']??config('constant.meta.default.description'));
        $this->theme->set('robot',$current_category['metarobot']??config('constant.meta.default.metarobot'));

        $data = [];
        $data['category'] = $current_category;
        if(empty($data['category']['news'])) $data['category']['news']=[];

        $data['list_news'] = [];
        if(!empty($current_category['news'])) $data['list_news'] = $this->repository->get_data_paginate('blog_news', ['id' => $current_category['news'],'status' => config('constant.status.on') ], 7);
        return $this->render('frontend.category',$data);
    }

    public function service($slug)
    {

        // $image = getimagesize(base_path().'/public/storage/blog/news/thumb/medium_foreign-investment-consultancy-in-vietnam_1581697706.jpg');

        $service = $this->repository->getbyAttribute(config('constant.database.service'),'slug',$slug)->first();
        if(empty($service)) abort(404, 'Service not found');

        if(session('hatlaw_language') == 'vi'){
            $this->theme->breadcrumb()->add($service->name, '');
            $this->theme->set('keywords',$service->metakey??config('constant.meta.default.keywords'));
            $this->theme->set('description',$service->metades??config('constant.meta.default.description'));
            $this->theme->set('robot',$service->metarobot??config('constant.meta.default.metarobot'));
        }
        if(session('hatlaw_language') == 'en'){
            $this->theme->breadcrumb()->add($service->en_name, '');
            $this->theme->set('keywords',$service->en_metakey??config('constant.meta.default.keywords'));
            $this->theme->set('description',$service->en_metades??config('constant.meta.default.description'));
            $this->theme->set('robot',$service->en_metarobot??config('constant.meta.default.metarobot'));
        }
        // $image_size = getimagesize(route('index').Storage::url(config('constant.image.service').'/'.$service->image));
        $data['frame'] = 'col';
        // if($image_size[0] > $image_size[1]) $data['frame'] = 'row';
        $data['service'] = $service;
        return $this->render('frontend.service', $data);
    }

    /**
     * show news
     * @return true
     */
    public function show_news($news_slug, BlogNewsRepository $blogNewsRepository)
    {
        if($this->repository->incrementData(config('constant.database.blog_news'), ['slug' => $news_slug],'hit',1)){
            $data['news'] = $this->repository->getbyAttribute(config('constant.database.blog_news'),'slug', $news_slug)->first();
            // set theme
            $this->theme->breadcrumb()->add($data['news']->title, '');
            if(!empty($data['news']->metakey)) $this->theme->set('keywords',$data['news']->metakey);
            if(!empty($data['news']->metades)) $this->theme->set('description', $data['news']->metades);
            if(!empty($data['news']->metarobot)) $this->theme->set('robot',$data['news']->metarobot);

            return $this->render('frontend.news', $data);
        }
    }
}
