<?php

namespace App\Http\Controllers\Backend\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BackendController as BackendController;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\Blog\StoreNews;

use App\Model\Admin;
use App\Model\BlogCategory;
use App\Model\BlogNews;
use App\Repositories\RedisRepository as RedisRepository;
use App\Repositories\Blog\NewsRepository as BlogNewsRepository;
use App\Repositories\Blog\CategoryRepository as BlogCategoryRepository;
use Intervention\Image\ImageManagerStatic as Image;
use URL, Input, HTML, Session, DB, Form, Gate;
use Yajra\Datatables\Datatables;

class NewsController extends BackendController
{
    private $blogNewsRepository;
	public function __construct(BlogNewsRepository $blogNewsRepository)
    {
        parent::__construct();

        // $this->authorizeResource('App\Model\BlogNews', 'blog_news');

        $this->blogNewsRepository = $blogNewsRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return $this->render('backend.blog.news.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_list_blog_news_ajax(Request $request)
    {
        $object = $this->blogNewsRepository->getAllWithRequestEloquent($request);
        $delete_soft = $this->check_auth_permis([config('constant.permissions.content.news').'_soft_delete']);

        return Datatables::of($object)
                ->addIndexColumn()
                ->editColumn('status', function($object){
                    return $object->status==1? '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>':'<button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button>';
                })
                ->editColumn('updated_at', function($object){
                    return date("d-m-Y H:i:s", strtotime($object->updated_at));
                })
                ->addColumn('action', function ($object) use($delete_soft){
                    $return = '';
                    $return .= '<a href="'.route('admin.blog_news.edit',$object->id).'" class="btn btn-sm btn-primary mr-2"><i class="fa fa-edit"></i></a>';
                    if($delete_soft && !$object->trashed())$return .= '<a onclick="return delete_confirm()" href="'.route('admin.blog_news.destroy',$object->id).'" class="btn btn-sm btn-danger "><i class="fa fa-trash-alt"></i></a>';
                    if($delete_soft && $object->trashed()) $return .= '<a onclick="return restore_confirm()" href="'.route('admin.blog_news.restore',$object->id).'" class="btn btn-sm btn-info "><i class="fa fa-recycle"></i></a>';
                    return $return;
                })
                ->rawColumns(['status','action'])
                ->make(true);

    }

    /**
     * Function description
     * @return true
     */
    public function listCategory()
    {
        $return = [];
        $listCategory = $this->redisRepository->getListBlogCategory();
        foreach($listCategory as $paCat){
            if(!isset($paCat['sub']))$return[$paCat['id']] = $paCat['name'];
            else{
                $return[$paCat['name']] = $paCat['sub_id_name'];
            }
        }
        return $return;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {

        $data['category']=[];$data['list_tag']=[];
        $data['category'] = $this->listCategory();
        $data['list_tag'] = $this->redisRepository->getListTag();
        $data['news']['old_category']=[];
        $data['news']['old_tag']=[];
        
        $data['news']['old_image'] = url('storage/basic/no_image.png');
        $data['news']['old_image_slide'] = url('storage/basic/no_image.png');
        return $this->render('backend.blog.news.create_edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(StoreNews $request)
    {
    	$input = $request->except('_token','image','image_slide');
    	$input['slug'] = $this->slugService->createSlug('blog_news', $request->title);
        $input['admin_id'] = Auth::guard('admin')->id();
        if(!isset($input['status'])) $input['status'] = 0;
        

        $upload_handle = true;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $newname = $input['slug'].'_'.time().'.'.$image->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($image, config('constant.image.blognews'), $newname,'yes','no');
            $input['image'] = $newname;
        }
        if($request->hasFile('image_slide')){
            $image = $request->file('image_slide');
            $newname = $input['slug'].'_'.(time()+2).'.'.$image->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($image, config('constant.image.blognews'), $newname,'yes','no');
            $input['image_slide'] = $newname;
        }
        if($this->blogNewsRepository->create($input)) return redirect()->route('admin.blog_news.list')->with('success', trans('form.create_success'));

        else return redirect()->route('admin.blog_news.list')->with('error', trans('form.create_fail'));
    }
    public function show()
    {
        // return view('admin::show');
    }


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($news_id)
    {
        // if(Gate::denies('post.update', BlogNews::findOrFail($news_id))) abort(403, 'Unauthorized action.');
        $data['category']=[];$data['list_tag']=[];
        $data['category'] = $this->listCategory();
        $data['list_tag'] = $this->redisRepository->getListTag();

    	$data['news'] = (array)$this->repository->getbyID(config('constant.database.blog_news'),$news_id);
        $data['news']['old_image'] = url('storage/basic/no_image.png');
        if($data['news']['image'] != NULL){
            $data['news']['old_image'] = url('storage'.config('constant.image.blognews').'/'.$data['news']['image']);
        }

        $data['news']['old_image_slide'] = url('storage/basic/no_image.png');
        if($data['news']['image_slide'] != NULL){
            $data['news']['old_image_slide'] = url('storage'.config('constant.image.blognews').'/'.$data['news']['image_slide']);
        }
        $data['news']['old_category']=[];
        $data['news']['old_tag']=[];
        $old_category = $this->repository->getbyTableField('blog_news_detail', ['blog_news_id' => $news_id]);
        $data['news']['old_category'] = [];
        if(!empty($old_category)){
            foreach($old_category as $cat){
                $data['news']['old_category'][] = $cat->blog_category_id;
            }
        }

        $old_tag = $this->blogNewsRepository->getbyTableField('tag_detail', ['blog_news_id' => $news_id]);
        if(!empty($old_tag)){
            foreach($old_tag as $tag){
                $data['news']['old_tag'][] = $tag->tag_id;
            }
        }
    	return $this->render('backend.blog.news.create_edit',$data);
    }
    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($news_id, StoreNews $request)
    {
    	$input = $request->except('_token','_method','id','image','image_slide','old_image','old_image_slide');
    	$input['slug'] = $this->slugService->createSlug('blog_news', $request->title, $news_id);
        $input['admin_id'] = Auth::guard('admin')->id();
        if(!isset($input['status'])) $input['status'] = 0;

        if($request->hasFile('image')){
            $image = $request->file('image');
            $newname = $input['slug'].'_'.time().'.'.$image->getClientOriginalExtension();
            $upload_image = $this->imageService->upload_image($image, config('constant.image.blognews'), $newname,'yes','no');
            $input['image'] = $newname;

            //remove old image when upload new avatar complete
            if($upload_image && strpos($request->old_avatar, 'no_image.png') == false )$this->imageService->removeOldImage(config('constant.image.blognews'), $this->getImageNamefromLink($request->old_image));
        }

        if($request->hasFile('image_slide')){
            $image = $request->file('image_slide');
            $newname = $input['slug'].'_'.(time()+2).'.'.$image->getClientOriginalExtension();
            $upload_image_slide = $this->imageService->upload_image($image, config('constant.image.blognews'), $newname,'yes','no');
            $input['image_slide'] = $newname;

            //remove old image slide when upload new avatar complete
            if($upload_image_slide && strpos($request->old_image_slide, 'no_image.png') == false )$this->imageService->removeOldImage(config('constant.image.blognews'), $this->getImageNamefromLink($request->old_image_slide));
        }
        if($this->blogNewsRepository->update($news_id, $input)) return redirect()->route('admin.blog_news.list')->with('success', trans('form.update_success'));
        else return redirect()->route('admin.blog_news.list')->with('error', trans('form.update_fail'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($news_id)
    {
        // if(Gate::denies('post.delete', BlogNews::findOrFail($news_id))) abort(403, 'Unauthorized action.');
    	if($this->blogNewsRepository->destroy($news_id))
        {
            return redirect()->route('admin.blog_news.list')->with('success', trans('form.delete_success'));
        }
        else return redirect()->route('admin.blog_news.list')->with('error', trans('form.delete_fail'));
    }

    public function restore($news_id)
    {

        if($this->blogNewsRepository->restore($news_id))
        {
            return redirect()->route('admin.blog_news.list')->with('success', trans('form.restore_success'));
        }
        else return redirect()->route('admin.blog_news.list')->with('error', trans('form.restore_fail'));
    }
}
