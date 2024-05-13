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
use URL, Input, HTML, Session, DB, Form, Gate, Theme;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;

class ProductController extends BackendController
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
        $this->theme->asset()->serve('datatable');

        if($this->check_auth_permis(['product_category_view']))
        return $this->render('backend.shop.product.index');
    }


    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_list_product_ajax(Request $request)
    {
        $product = $this->shopRepo->getAllProductWithRequestEloquent($request);
        $delete_soft = $this->check_auth_permis([config('constant.permissions.content.product').'_soft_delete']);
        return Datatables::of($product)
                ->addIndexColumn()
                ->editColumn('status_data', function($object){
                    return $object->status==1? '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>':'<button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button>';
                })
                ->editColumn('updated_at', function($object){
                    return date("d-m-Y H:i:s", strtotime($object->updated_at));
                })
                ->addColumn('action', function ($object) use($delete_soft){
                    $disable=false;
                    if( $object->trashed() || $object->status == config('constant.status.off')) $disable=true;

                    $return = '<a href="'.route('admin.product.edit',$object->id).'" class="btn btn-xs btn-primary mr-2"><i class="fa fa-edit"></i></a>';
                    if($delete_soft && $disable == false) $return .= '<a onclick="return delete_confirm()" href="'.route('admin.product.destroy',[$object->id, 'soft']).'" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt"></i></a>';
                    if($delete_soft && $disable == true) $return .= '<a onclick="return restore_confirm()" href="'.route('admin.product.restore',$object->id).'" class="btn btn-xs btn-info"><i class="fa fa-recycle"></i></a>';
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
    	$data['category_list'] = $this->listProductCategories();

        $brands = $this->shopRepo->getBrandList();
        foreach($brands as $brand){
            $data['brand_list'][$brand->id] = $brand->name;
        }
        if($this->check_auth_permis(['product_create']))
        return $this->render('backend.shop.product.create_edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    	$input = $request->except('_token','image');
        if(!isset($input['status'])) $input['status'] = 0;
        $input['slug'] = $this->slugService->createSlug('services', $request->name);
        $input['admin_id'] = Auth()->guard('admin')->user()->id;
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        
        $upload_handle = true;
        if($request->hasFile('image')){
            $month_year = Carbon::now()->format('my');
            foreach($request->image as $key =>  $image){
                $upload_handle = true;
                $newname = $input['slug'].'_'.(time()+$key+1).'.'.$image->getClientOriginalExtension();
                $upload_handle = $this->imageService->upload_image($image, config('constant.image.product').'/'.$month_year, $newname,'yes','yes');
                if(!$upload_handle) break;
                if($upload_handle) $input['images'][] = $month_year.'/'.$newname;
            }
        }
        if($upload_handle){
            if($this->shopRepo->createProduct($input)) return redirect()->route('admin.product.list')->with('success', trans('form.create_success'));

            else return redirect()->route('admin.product.list')->with('error', trans('form.create_fail'));
        }
        else return redirect()->route('admin.product.list')->with('error', trans('form.not_permission'));

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
    public function edit($product_id)
    {
    	$data['product'] = (array)$this->shopRepo->getbyID(config('constant.database.product'),$product_id);
        

        $product_images = $this->shopRepo->getProductImage($product_id);
        if(count($product_images) == 0) $data['old_image'][] = url('storage/basic/no_image.png');
        foreach($product_images as $key => $image){
            $data['old_image']['image_'.$key] = $image->image;
        }

        $brands = $this->shopRepo->getBrandList();
        foreach($brands as $brand){
            $data['brand_list'][$brand->id] = $brand->name;
        }
        
        $old_brand = $this->shopRepo->getProductBrand($product_id);
        $data['old_brand'] = [];
        if(count($old_brand) > 0){
            foreach($old_brand as $brand){
                $data['old_brand'][] = $brand->brand_id;
            }
        }

        $data['category_list'] = $this->listProductCategories();

        $old_category = $this->shopRepo->getProductCategory($product_id);
        $data['old_category'] = [];
        if(count($old_category) > 0){
            foreach($old_category as $cat){
                $data['old_category'][] = $cat->category_id;
            }
        }
        if($this->check_auth_permis('product_update')) return $this->render('backend.shop.product.create_edit',$data);
    }

    public function listProductCategories()
    {
        $return = [];
        $listCategory = $this->redisRepository->getListProductCategory();
        foreach($listCategory as $id => $paCat){
            if(!isset($paCat['sub'])) $return[$id] = $paCat;
            else{
                foreach($paCat['sub'] as $id_sub => $sub){
                    $return[$paCat['name']][$id_sub] = $sub['name'];
                }
            }
        }
        return $return;
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($product_id, Request $request)
    {
    	$input = $request->except('_method','_token','id','image','old_image');
        if(!isset($input['status'])) $input['status'] = 0;
        $input['slug'] = $this->slugService->createSlug('services', $request->name);
        $input['admin_id'] = Auth()->guard('admin')->user()->id;
        $input['updated_at'] = Carbon::now();
        $upload_handle = true;
        if($request->hasFile('image')){
            $month_year = Carbon::now()->format('my');
            foreach($request->image as $key =>  $image){
                $upload_handle = true;
                $newname = $input['slug'].'_'.(time()+$key+1).'.'.$image->getClientOriginalExtension();
                $upload_handle = $this->imageService->upload_image($image, config('constant.image.product').'/'.$month_year, $newname,'yes','yes');
                if($upload_handle) $input['images'][] = $month_year.'/'.$newname;
            }

            // if($upload_handle){
            //     //remove old product image
            //     $old_images = json_decode($request->old_image,true);
            //     foreach($old_images as $old_image){
            //         if(strpos($old_image, 'no_image') == false ){
            //             $this->imageService->removeOldImage(config('constant.image.product'), $old_image);
            //         }

            //         $this->shopRepo->removeProductImage($product_id, $old_image);
            //     }
                
            // }
        }

        if($upload_handle){
            if($this->shopRepo->updateProduct($product_id, $input)) return redirect()->route('admin.product.list')->with('success', trans('form.update_success'));
            else return redirect()->route('admin.product.list')->with('error', trans('form.update_fail'));
        }
        else return redirect()->route('admin.product.list')->with('error', trans('form.not_permission'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($product_id, $option)
    {
        if($this->shopRepo->destroy($product_id, $option))
        {
            return redirect()->route('admin.product.list')->with('success', trans('form.delete_success') );
        }
        else return redirect()->route('admin.product.list')->with('error', trans('form.delete_fail'));
    }
    public function restore($product_id)
    {
        if($this->check_auth_permis('service_soft_delete')){
            if($this->shopRepo->restore($product_id))
            {
                return redirect()->route('admin.product.list')->with('success', trans('form.restore_success'));
            }
            else return redirect()->route('admin.product.list')->with('error', trans('form.restore_fail'));
        }
    }
    public function delete_product_image($product_id, $image)
    {
        if($this->shopRepo->delete_product_image($product_id, $image))
        {
            $image_array = explode('&', $image);
            $this->imageService->removeOldImage(config('constant.image.product'), $image_array[1], $image_array[0]);

            return redirect()->route('admin.product.edit',$product_id)->with('success', trans('form.delete_success') );
        }
        else return redirect()->route('admin.product.edit',$product_id)->with('error', trans('form.delete_fail'));
    }
}
