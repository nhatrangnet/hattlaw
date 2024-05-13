<?php

namespace App\Http\Controllers\Backend\Gallery;

use App\Http\Controllers\BackendController as BackendController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\GalleryRepository;
use URL, Input, Validator, HTML, Session, DB, Theme, File, Carbon, Auth;
use Yajra\Datatables\Datatables;

use Intervention\Image\ImageManagerStatic as Image;

class CategoryController extends BackendController
{
    private $galleryRepository;
    public function __construct()
    {
        parent::__construct();
        $this->galleryRepository = new GalleryRepository;
    }

    /**
     * Function description
     * @return true
     */
    public function index()
    {
        return $this->render('backend.gallery.category.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return $this->render('backend.gallery.category.create_edit',$data);
    }
    public function store(Request $request)
    {
        $input = $request->except('_token');
        $input['slug'] = $this->slugService->createSlug('gallery_category', $request->name);
        
        if($this->galleryRepository->insertTableValue('gallery_category',$input)) return redirect()->route('admin.gallery_category.index')->with('success', trans('form.create_success'));

        else return redirect()->route('admin.gallery_category.index')->with('error', trans('form.create_fail'));
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
        $data = [];
        $data['category']= (array)$this->repository->getonlyTableField('gallery_category',['id' => $category_id]);
        return $this->render('backend.gallery.category.create_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($category_id, Request $request)
    {
        $input = $request->except('_token','_method', 'cover');
        $input['slug'] = $this->slugService->createSlug('gallery_category', $request->name);
        if(!isset($input['status'])) $input['status'] = 0;
        $input['admin_id'] = Auth::guard('admin')->id();
        $upload_handle = true;

        if($request->hasFile('cover')){
            $newname = $input['slug'].'_'.time().'.'.$request->cover->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($image, config('constant.image.gallerycat'), $newname,'yes','yes');

            if($upload_handle){
                $input['cover'] = $newname;
                //remove old avatar when upload new avatar complete
                if(!empty($old_cover) && strpos($old_cover, 'no_image') == false ) $this->imageService->removeOldImage(config('constant.image.gallerycat'), $old_cover);
            }
        }
        if($this->galleryRepository->update($category_id, $input)) return redirect()->route('admin.gallery_category.index')->with('success', trans('form.update_success'));
        else return redirect()->route('admin.gallery_category.index')->with('error', trans('form.update_fail'));
    }
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_list_gallery_category_ajax(Request $request)
    {
        $category = $this->galleryRepository->getAllWithRequest($request);
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
                    $return = '<a href="'.route('admin.gallery_category.edit',$category->id).'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                        ';
                    if($category->status == config('constant.status.on')) $return .= '<a onclick="return delete_confirm()" href="'.route('admin.gallery_category.destroy',[$category->id,'soft']).'" class="btn btn-xs btn-danger"><i class="fa fa-trash-alt"></i></a>';
                    if($category->status == config('constant.status.off')) $return .= '<a onclick="return restore_confirm()" href="'.route('admin.gallery_category.restore',$category->id).'" class="btn btn-xs btn-info restore_confirm"><i class="fa fa-recycle"></i></a>';
                    return $return;
                })
                ->rawColumns(['status','action'])
                ->make(true);

    }
}
