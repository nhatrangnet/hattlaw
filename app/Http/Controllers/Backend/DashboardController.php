<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController as BackendController;
use Modules\Admin\Http\Requests\RegisterAdminRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;

use App\Repositories\AdminRepository;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\Gate;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use App\Model\Admin;
use App\Http\Requests\StoreAdmin;
use App\Http\Requests\UpdateAdmin;
use URL, Input, Validator, HTML, Session, DB, Theme, File;
use App\Model\BlogNewsDetail;
use Illuminate\Support\Carbon;

class DashboardController extends BackendController
{
    private $roleRepository;
    public function __construct()
    {
        parent::__construct();
        $this->roleRepository = new RoleRepository;
        $this->adminRepository = new AdminRepository;

        // session(['admin.name' => 'LyNguyen']); //set session
        // session(['admin.email' => 'cnttnt@gmail.com']);
        // $test = session('admin','Chau'); //get session, if NULL set to Chau
        // echo session()->has('admin.name'); //check is session admin.name exists and NOT NULL

        //cache
        // Cache::store('redis')->put('bar','baz',10);
        // echo Cache::get('bar2','aaa');
        // if (Cache::has('key')) {} //return false if not exists or NULL
        // $posts = Cache::remember('all_user_active', 1, function () {
        //     return Post::with('user')->orderBy('created_at', 'desc')->paginate();
        // });
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $admin_logged = Auth::guard('admin')->user();
        $admin_permis['permiss'] = [];
        $admin_permis['role'] = '';
        foreach( $admin_logged->roles as $role){
            $admin_permis['permiss'] += $role->permissions;
            $admin_permis['role'] .= $role->slug.',';
        }

        $data = array(
            'admin' => $admin_logged->only('email','name','phone','address','created_at')+ $admin_permis,
        );
        Session::put('admin', $data['admin']);

        $data['time_birthday_search'] = timeStatisticSearch();
        return $this->render('backend.index', $data);
    }

    



    public function getRegister()
    {
      return $this->render('admin::register');
    }

    public function postRegister(RegisterAdminRequest $request)
    {
    Auth::guard('admin')->login($this->create($request->all()));
    return redirect($this->redirectPath());
    }

    /**
     * Config admin page
     * @return true
     */
    public function config()
    {
        $config = $this->redisRepository->getConfig('config');
        $data['config'] = [];
        if(!empty($config)) $data['config'] = json_decode($config,true);
        return $this->render('backend.config', $data);
    }
    /**
     * Config admin save
     * @return true
     */
    public function config_save(Request $request)
    {
        $input['value'] = $request->except('_token', 'logo', 'watermark','defaultslide','old_default');
        $input['role'] = 'config';
        $upload_handle = true;
        if($request->hasFile('logo')){
            $image = $request->file('logo');
            $newname = 'logo.png';
            $upload_handle = $this->imageService->upload_image($image, '', $newname,'yes','no');
        }
        if($request->hasFile('watermark')){
            $image = $request->file('watermark');
            $newname = 'watermark.png';
            $upload_handle = $this->imageService->upload_image($image, '', $newname,'yes','no');
        }

        if($request->hasFile('defaultslide')){
            $defaultslide = [];
            foreach($request->defaultslide as $key =>  $image){
                $newname = 'default_'.(time()+$key+1).'.'.$image->getClientOriginalExtension();
                $upload_handle = $this->imageService->upload_image($image, config('constant.image.default'), $newname,'yes','no');
                if($upload_handle) $defaultslide[] = $newname;
            }
            if(!empty($defaultslide)){
                $input['value']['defaultslide'] = implode(',',$defaultslide);

                //remove old avatar when upload new avatar complete
                if(!empty($request->old_default)){
                    $old_cover_list = explode(',', $request->old_default);
                    foreach($old_cover_list as $old_cover){
                        $this->imageService->removeOldImage(config('constant.image.default'), $old_cover);
                    }
                }
            }
        }
        
        if($upload_handle && $this->redisRepository->saveConfig($input)) return redirect()->route('admin.config')->with('success', trans('form.create_success'));

        else return redirect()->route('admin.config')->with('error', trans('form.create_fail'));
    }
    /**
     * Function introduce
     * @return true
     */
    public function introduce()
    {
        $introduce = $this->redisRepository->getConfig('introduce');

        $data['introduce'] = '';
        if(isset($introduce)) $data['introduce'] = json_decode($introduce,true);
        return $this->render('backend.introduce', $data);
    }
    /**
     * Function description
     * @return true
     */
    public function introduce_save(Request $request)
    {
        $value=[];
        $value['vi'] = $request->introduce;
        $value['en'] = $request->en_introduce;
        $input['value'] = $value;

        $input['role'] = 'introduce';

        if($this->redisRepository->saveConfig($input)) return redirect()->route('admin.introduce')->with('success', trans('form.create_success'));

        else return redirect()->route('admin.introduce')->with('error', trans('form.create_fail'));
    }
    /**
     * Function description
     * @return true
     */
    public function list_admins()
    {
        $data['role_search'] = $this->redisRepository->getListRole();
        array_unshift($data['role_search'], trans('form.all'));
        foreach($data['role_search'] as $key => $role){
            if($role == 'NhanVien') $data['role_user'] = $key;
        }
        return $this->render('backend.admin.index', $data);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_list_admin_ajax(Request $request)
    {
        $admins = $this->adminRepository->getAllAdminWithRequestEloquent($request);
        return Datatables::of($admins)
        ->addIndexColumn()
        ->editColumn('updated_at', function($object){
            return date("d-m-Y H:i:s", strtotime($object->updated_at));
        })
        ->editColumn('status_data', function($object){

            return $object->status==1? '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>':'<button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button>';
        })
        ->editColumn('role', function($object){

            $return = '';
            foreach($object->roles as $role){
                $return .= $role->name.',';
            }
            return rtrim($return,',');
        })
        ->addColumn('action', function ($object) {
            $return = '<a href="'.route('admin.edit',$object->id).'" class="btn btn-sm btn-primary mr-2" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><i class="fa fa-edit"></i></a>';
            if(!$object->isSuperAdmin() && !$object->trashed()) $return .= '<a onclick="return delete_confirm()" href="'.route('admin.destroy',[$object->id, 'soft']).'" class="btn btn-sm btn-secondary"><i class="fa fa-trash-alt"></i></a>';
            if(!$object->isSuperAdmin() && $object->trashed()) $return .= '<a onclick="return restore_confirm()" href="'.route('admin.restore',$object->id).'" class="btn btn-sm btn-secondary"><i class="fa fa-recycle"></i></a>';
            return $return;
        })
        ->rawColumns(['action','status_data'])
        ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $data['listRole'] = $this->redisRepository->getListRoleId();
        $data['admin']['roles'] = [];
        $data['admin']['old_avatar'] = url('storage/basic/no_image.png');
        $data['admin']['old_image'] = url('storage/basic/no_image.png');
        $data['metadata'] = [];

        return $this->render('backend.admin.create_edit',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(StoreAdmin $request)
    {
        $input = $request->except('_method','_token','role_ids','avatar','old_avatar','password_confirmation');
        $input['slug'] = $this->slugService->createSlug('admins', $request->name);
        if(!isset($input['status'])) $input['status'] = 0;

        $upload_handle = true;
        if($request->hasFile('avatar')){
            $image = $request->file('avatar');
            $newname = $input['slug'].'_'.time().'.'.$image->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($image, config('constant.image.admin').config('constant.image.avatar'), $newname,'yes','yes');
            $input['avatar'] = $newname;
        }
        if($upload_handle){
            if($this->adminRepository->create($input, $request->role_ids)) return redirect()->route('admin.list')->with('success', trans('form.create_success'));
            else return redirect()->route('admin.list')->with('error', trans('form.create_fail'));
        }
        else return redirect()->route('admin.list')->with('error', trans('form.upload_fail'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        echo 'show';
        // return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($admin_id)
    {
        $data['admin'] = (array)$this->repository->getbyID('admins',$admin_id);
        unset($data['admin']['email_verified_at']);
        unset($data['admin']['remember_token']);
        unset($data['admin']['password']);

        $data['admin']['old_avatar'] = url('storage/basic/no_image.png');
        if($data['admin']['avatar'] != NULL){
            $data['admin']['old_avatar'] = url('storage'.config('constant.image.admin').config('constant.image.avatar').'/'.$data['admin']['avatar']);
        }

        $data['admin']['old_image'] = Storage::url('basic/no_image.png');
        if($data['admin']['image'] != NULL){
            $data['admin']['old_image'] = url('storage'.config('constant.image.admin').config('constant.image.avatar').'/'.$data['admin']['image']);
        }

        $data['listRole'] = $this->redisRepository->getListRoleId();
        $roles = $this->roleRepository->getRolesbyAdmin($data['admin']['id']);
        $data['admin']['roles'] = [];
        foreach($roles as $role){
            $data['admin']['roles'][$role->id] = $role->name;
        }
        $data['metadata'] = json_decode($data['admin']['metadata'], true);
        return $this->render('backend.admin.create_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($admin_id, UpdateAdmin $request)
    {
        $input = $request->except('_method','_token','role_ids','avatar','metadata','old_avatar','old_image','old_roles');
        $input['slug'] = $this->slugService->createSlug('admins', $request->name, $admin_id);
        if(!isset($input['status'])) $input['status'] = 0;

        $upload_handle = true;
        if($request->hasFile('avatar')){
            $avatar = $request->file('avatar');
            $newname = $input['slug'].'_avatar_'.time().'.'.$avatar->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($avatar, config('constant.image.admin').config('constant.image.avatar'), $newname,'yes','yes');

            //remove old avatar when upload new avatar complete
            if($upload_handle && strpos($request->old_avatar, 'no_image.png') == false )$this->imageService->removeOldImage(config('constant.image.admin').config('constant.image.avatar'), $request->old_avatar);

            $input['avatar'] = $newname;
        }
        if($request->hasFile('image')){
            $image = $request->file('image');
            $newname = $input['slug'].'_image_'.time().'.'.$image->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($image, config('constant.image.admin').config('constant.image.avatar'), $newname,'yes','yes');

            //remove old image when upload new image complete
            if($upload_handle && strpos($request->old_image, 'no_image.png') == false )$this->imageService->removeOldImage(config('constant.image.admin').config('constant.image.avatar'), $request->old_image);

            $input['image'] = $newname;
        }
        $input['metadata'] = json_encode($request->metadata);
        if($upload_handle){
            //update role
            $old_roles = array_keys(json_decode($request->old_roles,true));
            $this->adminRepository->update_role($admin_id, $old_roles, $request->role_ids);
            
            if($this->adminRepository->update($admin_id, $input)) return redirect()->route('admin.list')->with('success', trans('form.update_success'));
            else return redirect()->route('admin.list')->with('error', trans('form.update_fail'));
        }
        else return redirect()->route('admin.list')->with('error', trans('form.upload_fail'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($admin_id, $option)
    {
        $old_roles=[];

        if($option == 'force'){
            $roles = $this->roleRepository->getRolesbyAdmin($admin_id);

            if(count($roles) > 0){
                foreach($roles as $role){
                    $old_roles[] = $role->id;
                }
                if(in_array(1, $old_roles)) return redirect()->route('admin.list')->with('error', trans('form.delete_fail'));
            }
        }
        if($this->adminRepository->destroy($admin_id, $option, $old_roles))
        {
            if($option == 'force'){

            }

            return redirect()->route('admin.list')->with('success', trans('form.delete_success'));
        }
        else return redirect()->route('admin.list')->with('error', trans('form.delete_fail'));
    }
    public function restore($admin_id)
    {
        if($this->adminRepository->restore($admin_id))
        {
            return redirect()->route('admin.list')->with('success', trans('form.restore_success'));
        }
        else return redirect()->route('admin.list')->with('error', trans('form.restore_fail'));
    }

    public function changeLanguage($language)
    {
        Session::put('website_language_admin', $language);
        return redirect()->back();
    }
}
