<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController as BackendController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Model\Admin;
use App\Model\Service;
use App\Repositories\RedisRepository as RedisRepository;
use App\Repositories\ServiceRepository as ServiceRepository;

use Intervention\Image\ImageManagerStatic as Image;
use URL, Input, HTML, Session, DB, Form, Gate;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;

class ServiceController extends BackendController
{
	private $serviceRepo;
	public function __construct(ServiceRepository $serviceRepo)
    {
        parent::__construct();
        $this->serviceRepo = $serviceRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if($this->check_auth_permis(['service_view']))
        return $this->render('backend.service.index');
    }


    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_list_service_ajax(Request $request)
    {
        $services = $this->serviceRepo->getAllServiceWithRequestEloquent($request);
        $delete_soft = $this->check_auth_permis([config('constant.permissions.content.service').'_soft_delete']);

        return Datatables::of($services)
                ->addIndexColumn()
                ->editColumn('status_data', function($object){
                    return $object->status==1? '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>':'<button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button>';
                })
                ->editColumn('updated_at', function($object){
                    return date("d-m-Y H:i:s", strtotime($object->updated_at));
                })
                ->addColumn('action', function ($user) use($delete_soft){
                    $return = '<a href="'.route('admin.service.edit',$user->id).'" class="btn btn-xs btn-primary mr-2"><i class="fa fa-edit"></i></a>';
                    if($delete_soft && !$user->trashed()) $return .= '<a onclick="return delete_confirm()" href="'.route('admin.service.destroy',[$user->id, 'soft']).'" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt"></i></a>';
                    if($delete_soft && $user->trashed()) $return .= '<a onclick="return restore_confirm()" href="'.route('admin.service.restore',$user->id).'" class="btn btn-xs btn-info"><i class="fa fa-recycle"></i></a>';
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
        $parent_service = $this->serviceRepo->getParentService()->pluck('name','id')->toArray();
        $data['parent_service'] = [0 => trans('form.service')] + $parent_service;
        return $this->render('backend.service.create_edit', $data);
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

        $upload_handle = true;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $newname = $input['slug'].'.'.$image->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($image, config('constant.image.service'), $newname,'yes','no');
            $input['image'] = $newname;
        }

        if($request->hasFile('cover')){
            $cover = $request->file('cover');
            $newname_cover = $input['slug'].'_cover.'.$cover->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($cover, config('constant.image.service'), $newname_cover,'yes','no');
            $input['cover'] = $newname_cover;
        }


        if($this->check_auth_permis('service_create') && $upload_handle){
            if($this->serviceRepo->insertTableValue('services',$input)) return redirect()->route('admin.service.list')->with('success', trans('form.create_success'));

            else return redirect()->route('admin.service.list')->with('error', trans('form.create_fail'));
        }
        else return redirect()->route('admin.list')->with('error', trans('form.not_permission'));

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
    public function edit($service_id)
    {
    	$data['service'] = (array)$this->serviceRepo->getbyID(config('constant.database.service'),$service_id);
        $parent_service = $this->serviceRepo->getParentService()->pluck('name','id')->toArray();
        unset($parent_service[$service_id]);
        $data['parent_service'] = [0 => trans('form.service')] + $parent_service;


        if($this->check_auth_permis('service_update')) return $this->render('backend.service.create_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($service_id, Request $request)
    {
    	$input = $request->except('_method','_token');
        if(!isset($input['status'])) $input['status'] = 0;
        $input['updated_at'] = Carbon::now();

        $input['slug'] = $this->slugService->createSlug('services', $request->name);

        if($request->hasFile('image')){
            $image = $request->file('image');
            $newname = $input['slug'].'.'.$image->getClientOriginalExtension();
            $upload_image = $this->imageService->upload_image($image, config('constant.image.service'), $newname,'yes','no');
            $input['image'] = $newname;
        }

        if($request->hasFile('cover')){
            $cover = $request->file('cover');
            $newname_cover = $input['slug'].'_cover.'.$cover->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($cover, config('constant.image.service'), $newname_cover,'yes','no');
            $input['cover'] = $newname_cover;
        }

        if($this->serviceRepo->updateTableValue(config('constant.database.service'),'id',$service_id, $input)) return redirect()->route('admin.service.list')->with('success', trans('form.update_success'));
        else return redirect()->route('admin.service.list')->with('error', trans('form.update_fail'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($service_id, $option)
    {
        if($this->serviceRepo->destroy($service_id, $option))
        {
            return redirect()->route('admin.service.list')->with('success', trans('form.delete_success') );
        }
        else return redirect()->route('admin.list')->with('error', trans('form.delete_fail'));
    }
    public function restore($service_id)
    {
        if($this->check_auth_permis('service_soft_delete')){
            if($this->serviceRepo->restore($service_id))
            {
                return redirect()->route('admin.service.list')->with('success', trans('form.restore_success'));
            }
            else return redirect()->route('admin.service.list')->with('error', trans('form.restore_fail'));
        }
    }
}
