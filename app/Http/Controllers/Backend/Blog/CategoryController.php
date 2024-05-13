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

use App\Http\Requests\Blog\StoreCategory;

use App\Model\Admin;
use App\Model\BlogCategory;
use App\Model\BlogNews;
use App\Repositories\RedisRepository as RedisRepository;
use App\Repositories\Blog\CategoryRepository as BlogCategoryRepository;
use Intervention\Image\ImageManagerStatic as Image;
use URL, Input, HTML, Session, DB, Form;
use Yajra\Datatables\Datatables;
use File;

class CategoryController extends BackendController
{
    private $blogCategoryRepository;
	public function __construct(BlogCategoryRepository $blogCategoryRepository)
    {
        parent::__construct();
        $this->blogCategoryRepository = $blogCategoryRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return $this->render('backend.blog.category.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_list_blog_category_ajax(Request $request)
    {
        $category = $this->blogCategoryRepository->getAllWithRequest($request);
        $delete_permis = $this->check_auth_permis([config('constant.permissions.content.category').'_soft_delete']);
        return Datatables::of($category)
                ->addIndexColumn()
                ->editColumn('status', function($object){
                    return $object->status==1? '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>':'<button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button>';
                })
                ->editColumn('updated_at', function($object){
                    return date("d-m-Y H:i:s", strtotime($object->updated_at));
                })
                ->addColumn('action', function ($category) {
                    $return = '<a href="'.route('admin.blog_category.edit',$category->id).'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                        ';
                    if($category->status == config('constant.status.on')) $return .= '<a onclick="return delete_confirm()" href="'.route('admin.blog_category.destroy',[$category->id,'soft']).'" class="btn btn-xs btn-danger"><i class="fa fa-trash-alt"></i></a>';
                    if($category->status == config('constant.status.off')) $return .= '<a onclick="return restore_confirm()" href="'.route('admin.blog_category.restore',$category->id).'" class="btn btn-xs btn-info restore_confirm"><i class="fa fa-recycle"></i></a>';
                    return $return;
                })
                ->rawColumns(['status','action'])
                ->make(true);

    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $rootCategory = $this->blogCategoryRepository->getRootCategory();
        $data['rootCategory_id'][0] = trans('form.parent_category');
        foreach($rootCategory as $cat){
            $data['rootCategory_id'][$cat->id] = $cat->name;
        }

        return $this->render('backend.blog.category.create_edit',$data);
    }


    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(StoreCategory $request)
    {
    	$input = $request->only('name', 'description','status','parent_id','metakey','metarobot');

        $input['slug'] = $this->slugService->createSlug('blog_categories', $request->name);
        if(!isset($input['parent_id'])) $input['parent_id'] = 0;
        if(!isset($input['status'])) $input['status'] = 0;
        $input['admin_id'] = Auth::guard('admin')->id();


        $upload_handle = true;
        if($request->hasFile('cover')){
            $image = $request->file('cover');
            $newname = $input['slug'].'_'.time().'.'.$image->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($image, config('constant.image.blogcat'), $newname,'yes','no');
            $input['cover'] = $newname;
        }
        

        if($upload_handle){
            if($this->blogCategoryRepository->create($input)) return redirect()->route('admin.blog_category.list')->with('success', trans('form.create_success'));

            else return redirect()->route('admin.blog_category.list')->with('error', trans('form.create_fail'));
        }
        else return redirect()->route('admin.list')->with('error', trans('form.upload_fail'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        // return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($category_id)
    {
    	$data['category'] = (array)$this->repository->getbyID(config('constant.database.blog_categories'),$category_id);
        
        if($data['category']['cover'] != NULL){
            $cover = explode(',', $data['category']['cover']);
            $data['category']['old_cover'] = '';
            foreach($cover as $cover){
                if(!empty($cover)){
                    $url = url('storage'.config('constant.image.blogcat').'/'.$cover);
                    $data['category']['old_cover'] .= "<img src='$url' alt='old-cover' class='img-fluid mw-20'>";
                }
            }
        }
        else{
            $no_image = url('storage/basic/no_image.png');
            $data['category']['old_cover'] = "<img src='$no_image' alt='old-cover' class='img-fluid'>";
        }

        $rootCategory = $this->blogCategoryRepository->getRootCategory();
        $data['rootCategory_id'][0] = trans('form.parent_category');
        foreach($rootCategory as $cat){
            if($cat->id == $category_id) continue;
            $data['rootCategory_id'][$cat->id] = $cat->name;
        }
    	return $this->render('backend.blog.category.create_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($category_id, StoreCategory $request)
    {
        $input = $request->only('name','description','status','parent_id','metakey','metarobot');
        $input['slug'] = $this->slugService->createSlug('blog_categories', $request->name, $category_id);
        if(!isset($input['parent_id'])) $input['parent_id'] = 0;
        if(!isset($input['status'])) $input['status'] = 0;
        $input['admin_id'] = Auth::guard('admin')->id();
        $upload_handle = true;
        if($request->hasFile('cover')){
            $cover = [];
            foreach($request->cover as $key =>  $image){
                $newname = $input['slug'].'_'.(time()+$key+1).'.'.$image->getClientOriginalExtension();
                $upload_handle = $this->imageService->upload_image($image, config('constant.image.blogcat'), $newname,'yes','no');
                if($upload_handle) $cover[] = $newname;
            }
            if(!empty($cover)){
                $input['cover'] = implode(',',$cover);

                //remove old vover when upload new cover complete
                $old_cover_list = explode(',', $request->old_cover);
                foreach($old_cover_list as $old_cover){
                    if(!empty($old_cover) && strpos($old_cover, 'no_image') == false ) $this->imageService->removeOldImage(config('constant.image.blogcat'), $old_cover);
                }
            }
        }
        

        if($this->blogCategoryRepository->update($category_id, $input)) return redirect()->route('admin.blog_category.list')->with('success', trans('form.update_success'));
        else return redirect()->route('admin.blog_category.list')->with('error', trans('form.update_fail'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($category_id, $option = 'soft')
    {
    	if($this->blogCategoryRepository->destroy($category_id, $option))
        {

            return redirect()->route('admin.blog_category.list')->with('success', trans('form.delete_success'));
        }
        else return redirect()->route('admin.blog_category.list')->with('error', trans('form.update_fail'));
        
    }
    public function restore($category_id)
    {
        if($this->blogCategoryRepository->restore($category_id))
        {
            return redirect()->route('admin.blog_category.list')->with('success', trans('form.update_success'));
        }
        else return redirect()->route('admin.blog_category.list')->with('error', trans('form.update_fail'));
    }
}
