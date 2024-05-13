<?php

namespace App\Http\Controllers\Backend\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BackendController as BackendController;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Model\Admin;
use App\Model\Category;
use App\Model\Product;
use App\Repositories\RedisRepository as RedisRepository;
use App\Repositories\ShopRepository as ShopRepository;

use Intervention\Image\ImageManagerStatic as Image;
use URL, Input, HTML, Session, DB, Form, Gate;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;

class CategoryController extends BackendController
{
    private $shopRepo;
	public function __construct(ShopRepository $shopRepo)
    {
        parent::__construct();
        $this->shopRepo = $shopRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if($this->check_auth_permis(['product_category_view']))
        return $this->render('backend.shop.category.index');
    }


    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_list_product_category_ajax(Request $request)
    {
        $services = $this->shopRepo->getAllCategoryWithRequestEloquent($request);
        $delete_soft = $this->check_auth_permis([config('constant.permissions.content.category').'_soft_delete']);

        return Datatables::of($services)
                ->addIndexColumn()
                ->editColumn('status_data', function($object){
                    return $object->status==1? '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>':'<button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button>';
                })
                ->editColumn('updated_at', function($object){
                    return date("d-m-Y H:i:s", strtotime($object->updated_at));
                })
                ->addColumn('action', function ($user) use($delete_soft){
                    $return = '<a href="'.route('admin.product-category.edit',$user->id).'" class="btn btn-xs btn-primary mr-2"><i class="fa fa-edit"></i></a>';
                    if($delete_soft && !$user->trashed()) $return .= '<a onclick="return delete_confirm()" href="'.route('admin.product-category.destroy',[$user->id, 'soft']).'" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt"></i></a>';
                    if($delete_soft && $user->trashed()) $return .= '<a onclick="return restore_confirm()" href="'.route('admin.product-category.restore',$user->id).'" class="btn btn-xs btn-info"><i class="fa fa-recycle"></i></a>';
                    return $return;
                })
                ->rawColumns(['status_data','action'])
                ->make(true);

    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
    	$rootCategory = $this->redisRepository->getRootProductCategory();
        $data['rootCategory_id'][0] = trans('form.parent_category');
        foreach($rootCategory as $cat){
            $data['rootCategory_id'][$cat->id] = $cat->name;
        }

        if($this->check_auth_permis(['product_category_create']))
        return $this->render('backend.shop.category.create_edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    	$input = $request->except('_token');
        if(!isset($input['status'])) $input['status'] = 0;
        $input['slug'] = $this->slugService->createSlug('services', $request->name);
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        $input['admin_id'] = Auth()->guard('admin')->user()->id;

        $upload_handle = true;
        if($request->hasFile('cover')){
            $image = $request->file('cover');
            $newname = $input['slug'].'_'.time().'.'.$image->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($image, config('constant.image.category'), $newname,'yes','yes');
            $input['cover'] = $newname;
        }
        

        if($this->check_auth_permis('product_category_create')){
            if($this->shopRepo->insertTableValue(config('constant.database.product_category'),$input)) return redirect()->route('admin.product-category.list')->with('success', trans('form.create_success'));

            else return redirect()->route('admin.product-category.list')->with('error', trans('form.create_fail'));
        }
        else return redirect()->route('admin.product-category.list')->with('error', trans('form.not_permission'));

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
    	$data['category'] = (array)$this->shopRepo->getbyID(config('constant.database.product_category'),$category_id);

        $data['category']['old_cover'] = url('storage/basic/no_image.png');
        if(!empty($data['category']['cover'])){            
            $data['category']['old_cover'] = url('storage'.config('constant.image.category').'/'.$data['category']['cover']);
        }

        $rootCategory = $this->redisRepository->getRootProductCategory();
        $data['rootCategory_id'][0] = trans('form.parent_category');
        foreach($rootCategory as $cat){
            $data['rootCategory_id'][$cat->id] = $cat->name;
        }
        if($this->check_auth_permis('service_update')) return $this->render('backend.shop.category.create_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($category_id, Request $request)
    {
    	$input = $request->except('_method','_token','old_cover');
        if(!isset($input['status'])) $input['status'] = 0;
        $input['slug'] = $this->slugService->createSlug('services', $request->name);
        $input['admin_id'] = Auth()->guard('admin')->user()->id;
        $input['updated_at'] = Carbon::now();

        $upload_handle = true;
        if($request->hasFile('cover')){
            $newname = $input['slug'].'_'.(time()+1).'.'.$request->cover->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($request->cover, config('constant.image.category'), $newname,'yes','yes');

            if($upload_handle){
                $input['cover'] = $newname;

                //remove old avatar when upload new avatar complete
                 if(!empty($request->old_cover) && strpos($request->old_cover, 'no_image') == false ) $this->imageService->removeOldImage(config('constant.image.category'), $request->old_cover);
            }
        }

	
        if($upload_handle && $this->shopRepo->updateTableValue(config('constant.database.product_category'),'id',$category_id, $input)) return redirect()->route('admin.product-category.list')->with('success', trans('form.update_success'));
        else return redirect()->route('admin.product-category.list')->with('error', trans('form.update_fail'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($category_id, $option)
    {
        if($this->shopRepo->destroy($category_id, $option))
        {
            return redirect()->route('admin.service.list')->with('success', trans('form.delete_success') );
        }
        else return redirect()->route('admin.list')->with('error', trans('form.delete_fail'));
    }
    public function restore($category_id)
    {
        if($this->check_auth_permis('service_soft_delete')){
            if($this->shopRepo->restore($category_id))
            {
                return redirect()->route('admin.service.list')->with('success', trans('form.restore_success'));
            }
            else return redirect()->route('admin.service.list')->with('error', trans('form.restore_fail'));
        }
    }
}
