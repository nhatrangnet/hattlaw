<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController as BackendController;
use Illuminate\Http\Request;
use App\Repositories\RoleRepository;
use URL, Input, HTML, Session, DB, Form, Auth;
use Yajra\Datatables\Datatables;
use App\Model\BlogNews;

class RoleController extends BackendController
{
	private $roleRepository;
    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct();
        $this->roleRepository = $roleRepository;
        
    }

    public function index()
    {
        $data = [];
        return $this->render('backend.role.index', $data);
    }
    public function get_list_role_ajax(Request $request)
    {
        $object = $this->roleRepository->getAllWithRequestEloquent($request);
        $delete_permis = $this->check_auth_permis([config('constant.permissions.content.role').'_soft_delete']);
        return Datatables::of($object)
                ->addIndexColumn()
                ->editColumn('status_data', function($object){
                    return $object->status==1? '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>':'<button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button>';

                })
                ->editColumn('updated_at', function($object){
                    return date("d-m-Y H:i:s", strtotime($object->updated_at));
                })
                ->addColumn('action', function ($object) use($delete_permis) {
                    $return = '<a href="'.route('admin.role.edit',$object->id).'" class="btn btn-xs btn-primary mr-2"><i class="fa fa-edit"></i></a>';
                    if($delete_permis == true && !$object->trashed()) $return .= '<a onclick="return delete_confirm()" href="'.route('admin.role.destroy',[$object->id, 'soft']).'" class="btn btn-xs btn-secondary"><i class="fa fa-trash-alt"></i></a>';
                    if($delete_permis == true && $object->trashed()) $return .= '<a onclick="return delete_confirm()" href="'.route('admin.role.restore',$object->id).'" class="btn btn-xs btn-info restore_confirm"><i class="fa fa-recycle"></i></a>';
                    return $return;
                })
                ->rawColumns(['action','status_data'])
                ->make(true); //or ->toJson()

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['permissions'] = list_permissions();
        $data['role']['permissions'] = [];
        return $this->render('backend.role.create_edit',$data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('_token','permiss');
        $input['slug'] = $this->slugService->createSlug('roles',$request->name);
        if(!isset($input['status'])) $input['status'] = 0;
        $input['permissions'] = array_fill_keys(array_keys(array_flip($request->permiss)), true);

        if($this->roleRepository->create($input)) return redirect()->route('admin.role.list')->with('success', trans('form.create_success'));

        else return redirect()->route('admin.role.list')->with('error', trans('form.create_fail'));
    }

    /**
     * show
     * @return true
     */
    public function show()
    {
    	echo 'show';
        // return true;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\c  $c
     * @return \Illuminate\Http\Response
     */
    public function edit($role_id)
    {
        $data['role'] = (array)$this->roleRepository->getbyID($role_id);
        $data['role']['permissions'] = json_decode($data['role']['permissions'], true);
        $data['permissions'] = list_permissions();
        return $this->render('backend.role.create_edit',$data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\c  $c
     * @return \Illuminate\Http\Response
     */
    public function update($role_id, Request $request)
    {
        $input = $request->only('name','status');
        $input['slug'] = $this->slugService->createSlug('roles', $request->name, $role_id);
        if(!isset($input['status'])) $input['status'] = 0;

        $input['permissions'] = array_fill_keys(array_keys(array_flip($request->permiss)), true);
        if($input['permissions'] == json_decode($request->old_permiss,true)){
            unset($input['permissions']);
        }

        if($this->roleRepository->update($role_id, $input)) return redirect()->route('admin.role.list')->with('success', trans('form.update_success'));
        else return redirect()->route('admin.role.list')->with('error', trans('form.update_fail'));
    }
    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($role_id, $option = 'soft')
    {
        if($this->check_auth_permis([config('constant.permissions.content.role').'_'.$option.'_delete']) == false) return redirect()->route('admin.role.list')->with('error', trans('form.delete_fail'));


        if($this->roleRepository->destroy($role_id, $option))
        {
            return redirect()->route('admin.role.list')->with('success', trans('form.delete_success'));
        }
        else return redirect()->route('admin.role.list')->with('error', trans('form.delete_fail'));
    }
    public function restore($role_id)
    {
        if($this->roleRepository->restore($role_id))
        {
            return redirect()->route('admin.role.list')->with('success', trans('form.restore_success'));
        }
        else return redirect()->route('admin.role.list')->with('error', trans('form.restore_fail'));
    }
}