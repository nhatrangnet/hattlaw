<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController as BackendController;
use App\Repositories\TagRepository;
use Illuminate\Support\Facades\Auth;
use URL, Input, HTML, Session, DB, Form;
use Yajra\Datatables\Datatables;

class TagController extends BackendController
{
    private $tagRepository;
    public function __construct(TagRepository $tagRepository)
    {
        parent::__construct();
        $this->tagRepository = $tagRepository;
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->render('backend.tag.index');
    }


    public function get_list_tag_ajax(Request $request)
    {
        $object = $this->tagRepository->getAllWithRequest($request);
        return Datatables::of($object)
                ->addIndexColumn()
                ->editColumn('status_data', function($object){
                    return $object->status==1? '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>':'<button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button>';
                })
                ->addColumn('number_post', function($object){
                    return count($this->tagRepository->blogNewsPerTag($object->id));
                })
                ->editColumn('updated_at', function($object){
                    return date("d-m-Y H:i:s", strtotime($object->updated_at));
                })
                ->addColumn('action', function ($object) {
                    if($object->status == config('constant.status.on'))
                    return '<a href="'.route('admin.tag.edit',$object->id).'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                        <a onclick="return delete_confirm()" href="'.route('admin.tag.destroy',[$object->id, 'soft']).'" class="btn btn-xs btn-danger "><i class="fa fa-trash-alt"></i></a>';
                    if($object->status == config('constant.status.off'))
                    return '<a href="'.route('admin.tag.edit',$object->id).'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                        <a onclick="return restore_confirm()" href="'.route('admin.tag.restore',[$object->id]).'" class="btn btn-xs btn-info "><i class="fa fa-recycle"></i></a>';
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
        return $this->render('backend.tag.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['slug'] = $this->slugService->createSlug('tags',$request->name);
        if(!isset($input['status'])) $input['status'] = 0;
        $input['admin_id'] = Auth::guard('admin')->id();

        if($this->tagRepository->create($input)) return redirect()->route('admin.tag.list')->with('success', trans('form.create_success'));

        else return redirect()->route('admin.tag.list')->with('error', trans('form.create_fail'));
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
    public function edit($tag_id)
    {
        $data['tag'] = $this->tagRepository->getbyID($tag_id);
        return $this->render('backend.tag.create_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\c  $c
     * @return \Illuminate\Http\Response
     */
    public function update($tag_id, Request $request)
    {

        $input = $request->only('name','status');
        $input['slug'] = $this->slugService->createSlug('tags', $request->name, $tag_id);
        if(!isset($input['status'])) $input['status'] = 0;
        $input['admin_id'] = Auth::guard('admin')->id();
        if($this->tagRepository->update($tag_id, $input)) return redirect()->route('admin.tag.list')->with('success', trans('form.update_success'));
        else return redirect()->route('admin.tag.list')->with('error', trans('form.update_fail'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\c  $c
     * @return \Illuminate\Http\Response
     */
    public function destroy($tag_id, $option = 'soft')
    {
        if($this->tagRepository->destroy($tag_id, $option))
        {
            return redirect()->route('admin.tag.list')->with('success', trans('form.delete_success'));
        }
        else return redirect()->route('admin.tag.list')->with('error', trans('form.delete_fail'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\c  $c
     * @return \Illuminate\Http\Response
     */
    public function restore($tag_id)
    {
        if($this->tagRepository->restore($tag_id))
        {
            return redirect()->route('admin.tag.list')->with('success', trans('form.update_success'));
        }
        else return redirect()->route('admin.tag.list')->with('error', trans('form.update_fail'));
    }
}
