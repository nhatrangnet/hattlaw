<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController as BackendController;
use App\Repositories\ReviewRepository;
use Illuminate\Support\Facades\Auth;
use URL, Input, HTML, Session, DB, Form;
use Yajra\Datatables\Datatables;

class ReviewController extends BackendController
{
    private $reviewRepository;
    public function __construct(ReviewRepository $reviewRepository)
    {
        parent::__construct();
        $this->reviewRepository = $reviewRepository;
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->render('backend.review.index');
    }


    public function get_list_review_ajax(Request $request)
    {
        $object = $this->reviewRepository->getAllWithRequest($request);
        return Datatables::of($object)
                ->addIndexColumn()
                ->editColumn('status_data', function($object){
                    return $object->status==1? '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>':'<button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button>';
                })
                ->addColumn('text', function($object){
                    return subString($object->text, 30);
                })
                ->addColumn('rating1', function($object){
                    // return count($this->reviewRepository->blogNewsPerTag($object->id));
                })
                ->editColumn('updated_at', function($object){
                    return date("d-m-Y H:i:s", strtotime($object->updated_at));
                })
                ->addColumn('action', function ($object) {
                    if($object->status == config('constant.status.on'))
                    return '<a href="'.route('admin.reviews.edit',$object->id).'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                        <a onclick="return delete_confirm()" href="'.route('admin.reviews.destroy',[$object->id, 'soft']).'" class="btn btn-xs btn-danger "><i class="fa fa-trash-alt"></i></a>';
                    if($object->status == config('constant.status.off'))
                    return '<a href="'.route('admin.reviews.edit',$object->id).'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                        <a onclick="return restore_confirm()" href="'.route('admin.reviews.restore',[$object->id]).'" class="btn btn-xs btn-info "><i class="fa fa-recycle"></i></a>';
                })
                ->rawColumns(['action','status_data'])
                ->make(true); //or ->toJson()

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        for($i=1;$i<6;$i++){
            $data['rating'][$i] = $i.' star'; 
        }
        return $this->render('backend.review.create_edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('_token');
        if(!isset($input['status'])) $input['status'] = 0;

        $upload_handle = true;
        if($request->hasFile('profile_photo')){
            $profile_photo = $request->file('profile_photo');
            
            $newname = time().'.'.$profile_photo->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($profile_photo, config('constant.image.review'), $newname,'yes','no');
            $input['profile_photo'] = $newname;
        }


        if($this->reviewRepository->create($input)) return redirect()->route('admin.reviews.list')->with('success', trans('form.create_success'));

        else return redirect()->route('admin.reviews.list')->with('error', trans('form.create_fail'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\c  $c
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\c  $c
     * @return \Illuminate\Http\Response
     */
    public function edit($review_id)
    {
        $data['review'] = $this->reviewRepository->getbyID($review_id);
        
        for($i=1;$i<6;$i++){
            $data['rating'][$i] = $i.' star'; 
        }
        return $this->render('backend.review.create_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\c  $c
     * @return \Illuminate\Http\Response
     */
    public function update($review_id, Request $request)
    {

        $input = $request->except('_token','_method');
        if(!isset($input['status'])) $input['status'] = 0;
        
        $upload_handle = true;
        if($request->hasFile('profile_photo')){
            $profile_photo = $request->file('profile_photo');

            $newname = time().'.'.$profile_photo->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($profile_photo, config('constant.image.review'), $newname,'yes','no');
            $input['profile_photo'] = $newname;
        }

        if($this->reviewRepository->update($review_id, $input)) return redirect()->route('admin.reviews.list')->with('success', trans('form.update_success'));
        else return redirect()->route('admin.reviews.list')->with('error', trans('form.update_fail'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\c  $c
     * @return \Illuminate\Http\Response
     */
    public function destroy($review_id, $option = 'soft')
    {
        if($this->reviewRepository->destroy($review_id, $option))
        {
            return redirect()->route('admin.reviews.list')->with('success', trans('form.delete_success'));
        }
        else return redirect()->route('admin.reviews.list')->with('error', trans('form.delete_fail'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\c  $c
     * @return \Illuminate\Http\Response
     */
    public function restore($review_id)
    {
        if($this->reviewRepository->restore($review_id))
        {
            return redirect()->route('admin.reviews.list')->with('success', trans('form.update_success'));
        }
        else return redirect()->route('admin.reviews.list')->with('error', trans('form.update_fail'));
    }
}
