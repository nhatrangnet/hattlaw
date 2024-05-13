<?php

namespace App\Http\Controllers\Backend\Gallery;

use App\Http\Controllers\BackendController as BackendController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\GalleryRepository;
use URL, Input, Validator, HTML, Session, DB, Theme, File, Carbon;
use Yajra\Datatables\Datatables;
use Intervention\Image\ImageManagerStatic as Image;

class ImageController extends BackendController
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
        $this->theme->asset()->serve('lightbox');
        $category_list = $this->galleryRepository->get_list_gallery_category();
        $data=[];
        foreach($category_list as $key => $cat){
            $data['category_list'][$key] = $cat['name'];
        }
        return $this->render('backend.gallery.image.index', $data);
    }
    
    /**
     * Function description
     * @return true
     */
    public function create()
    {
        $category_list = $this->galleryRepository->get_list_gallery_category();
        $data=[];
        foreach($category_list as $key => $cat){
            $data['category_list'][$key] = $cat['name'];
        }        
        return $this->render('backend.gallery.image.create_edit', $data);
    }
    /**
     * Function description
     * @return true
     */
    public function store(Request $request)
    {
        $input = $request->except('_token');
        $upload_handle = true;
        if($request->hasFile('image')){
            foreach($request->image as $key =>  $image){
                $newname = $input['category'].'_'.(time()+$key+1).'.'.$image->getClientOriginalExtension();
                $upload_handle = $this->imageService->upload_image($image, config('constant.image.gallery').'/'.$input['category'], $newname,'yes','yes');

                if($upload_handle){
                    $this->repository->insertTableValueGetid('gallery_image',['gallery_category_id' => $input['category'], 'image' => $newname]);
                }
            }
        }
        if($upload_handle) return redirect()->route('admin.galleryimage.index')->with('success', trans('form.create_success'));

        else return redirect()->route('admin.galleryimage.index')->with('error', trans('form.create_fail'));
    }
    /**
     * Function description
     * @return true
     */
    public function ajax_image_by_gallery_category(Request $request)
    {
        $input = $request->all();
        $data['category'] = $input['category'];
        $data['images'] = $this->repository->getbyTableField('gallery_image',['status' => config('constant.status.on'), 'gallery_category_id' => $input['category']] );
        
        return $this->renderView('backend.gallery.image.list_image_by_category', $data);
    }
}
