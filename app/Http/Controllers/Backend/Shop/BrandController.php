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
use App\Repositories\ShopRepository as ShopRepository;

use Intervention\Image\ImageManagerStatic as Image;
use URL, Input, HTML, Session, DB, Form, Gate;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;

class BrandController extends BackendController
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
        return $this->render('backend.shop.brand.index');
    }


    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_list_brand_ajax(Request $request)
    {
        $services = $this->shopRepo->getListBrand($request);
        $delete_soft = $this->check_auth_permis([config('constant.permissions.content.category').'_soft_delete']);

        return Datatables::of($services)
                ->addIndexColumn()
                ->editColumn('status_data', function($object){
                    return $object->status==1? '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>':'<button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button>';
                })
                ->editColumn('updated_at', function($object){
                    return date("d-m-Y H:i:s", strtotime($object->updated_at));
                })
                ->addColumn('action', function ($object) use($delete_soft){
                    $return = '<a href="'.route('admin.brand.edit',$object->id).'" class="btn btn-xs btn-primary mr-2"><i class="fa fa-edit"></i></a>';
                    $return .= '<a onclick="return delete_confirm()" href="'.route('admin.brand.destroy', $object->id).'" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt"></i></a>';
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
        if($this->check_auth_permis(['brand_create']))
        return $this->render('backend.shop.brand.create_edit');
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
        $input['admin_id'] = Auth()->guard('admin')->user()->id;
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();

        $upload_handle = true;
        if($request->hasFile('cover')){
            $image = $request->file('cover');
            $newname = $input['slug'].'_'.time().'.'.$image->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($image, config('constant.image.brand'), $newname,'yes','yes');
            $input['cover'] = $newname;
        }

        if($this->check_auth_permis('brand_create')){
            if($this->shopRepo->insertTableValue(config('constant.database.brand'),$input)) return redirect()->route('admin.brand.list')->with('success', trans('form.create_success'));

            else return redirect()->route('admin.brand.list')->with('error', trans('form.create_fail'));
        }
        else return redirect()->route('admin.brand.list')->with('error', trans('form.not_permission'));

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
    public function edit($brand_id)
    {
    	$data['brand'] = (array)$this->shopRepo->getbyID(config('constant.database.brand'),$brand_id);

        $data['brand']['old_cover'] = url('storage/basic/no_image.png');
        if(!empty($data['brand']['cover'])){            
            $data['brand']['old_cover'] = url('storage'.config('constant.image.brand').'/'.$data['brand']['cover']);
        }

        if($this->check_auth_permis('service_update')) return $this->render('backend.shop.brand.create_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($brand_id, Request $request)
    {
    	$input = $request->except('_method','_token','old_cover');
        if(!isset($input['status'])) $input['status'] = 0;
        $input['slug'] = $this->slugService->createSlug('services', $request->name);
        $input['admin_id'] = Auth()->guard('admin')->user()->id;
        $input['updated_at'] = Carbon::now();

        $upload_handle = true;
        if($request->hasFile('cover')){
            $newname = $input['slug'].'_'.(time()+1).'.'.$request->cover->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($request->cover, config('constant.image.brand'), $newname,'yes','yes');

            if($upload_handle){
                $input['cover'] = $newname;

                //remove old avatar when upload new avatar complete
                 if(!empty($request->old_cover) && strpos($request->old_cover, 'no_image') == false ) $this->imageService->removeOldImage(config('constant.image.brand'), $request->old_cover);
            }
        }

        if($upload_handle && $this->shopRepo->updateTableValue(config('constant.database.brand'),'id',$brand_id, $input)) return redirect()->route('admin.brand.list')->with('success', trans('form.update_success'));
        else return redirect()->route('admin.brand.list')->with('error', trans('form.update_fail'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($brand_id, $option)
    {
        if($this->shopRepo->destroy($brand_id, $option))
        {
            return redirect()->route('admin.service.list')->with('success', trans('form.delete_success') );
        }
        else return redirect()->route('admin.list')->with('error', trans('form.delete_fail'));
    }
    public function restore($brand_id)
    {
        if($this->check_auth_permis('service_soft_delete')){
            if($this->shopRepo->restore($brand_id))
            {
                return redirect()->route('admin.service.list')->with('success', trans('form.restore_success'));
            }
            else return redirect()->route('admin.service.list')->with('error', trans('form.restore_fail'));
        }
    }
}
