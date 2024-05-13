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
use App\Model\User;
use App\Repositories\RedisRepository as RedisRepository;
use App\Repositories\UserRepository as UserRepository;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;

use Intervention\Image\ImageManagerStatic as Image;
use URL, Input, HTML, Session, DB, Form, Gate;
use Illuminate\Support\Carbon;

use Yajra\Datatables\Datatables;
class UserController extends BackendController
{
	private $userRepo;
	public function __construct(UserRepository $userRepo)
    {
        parent::__construct();
        $this->userRepo = $userRepo;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

    //get user on current month by week
    // $start = Carbon::now()->startOfWeek()->day;
    // $start_month = Carbon::now()->month;

    // $end = Carbon::now()->endOfWeek()->day;
    // $end_month = Carbon::now()->endOfWeek()->month;

    // if($start_month != $end_month){
    //     $end = Carbon::now()->endOfMonth()->day;
    // }

    // $user = DB::table('users')->where('status', config('constant.status.on'))->whereMonth('birthday', $start_month)->whereDay('birthday','>=', $start)->whereDay('birthday','<=', $end)->get();

    // echo '<pre>';print_r($user);die;

        if($this->check_auth_permis(['user_view']))
        return $this->render('backend.user.index');
    }


    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_list_user_ajax(Request $request)
    {
        $users = $this->userRepo->getAllUserWithRequestEloquent($request);
        $delete_soft = $this->check_auth_permis([config('constant.permissions.content.user').'_soft_delete']);

        return Datatables::of($users)
                ->addIndexColumn()
                ->editColumn('status_data', function($object){
                    return $object->status==1? '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>':'<button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button>';
                })
                ->editColumn('birthday', function($object){
                    return date('d-m-Y', strtotime($object->birthday));
                })
                ->editColumn('updated_at', function($object){
                    return date("d-m-Y H:i:s", strtotime($object->updated_at));
                })
                ->addColumn('action', function ($user) use($delete_soft){
                    $return = '<a href="'.route('admin.user.edit',$user->id).'" class="btn btn-xs btn-primary mr-2"><i class="fa fa-edit"></i></a>';
                    if($delete_soft && !$user->trashed()) $return .= '<a onclick="return delete_confirm()" href="'.route('admin.user.destroy',[$user->id, 'soft']).'" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt"></i></a>';
                    if($delete_soft && $user->trashed()) $return .= '<a onclick="return restore_confirm()" href="'.route('admin.user.restore',$user->id).'" class="btn btn-xs btn-info"><i class="fa fa-recycle"></i></a>';
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
        if($this->check_auth_permis(['user_create']))
        return $this->render('backend.user.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(StoreUser $request)
    {
    	$input = $request->except('birthday','_token');
        if(!isset($input['status'])) $input['status'] = 0;
        $input['birthday'] = Carbon::create($request->input('birthday'))->toDateString();
        $input['slug'] = $this->slugService->createSlug('users', $request->name);
        $upload_handle = true;
        if($request->hasFile('avatar')){
            $avatar = $request->file('avatar');
            $newname = $input['slug'].'_'.time().'.'.$avatar->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($avatar, config('constant.image.user'), $newname,'yes','no');
            $input['avatar'] = $newname;
        }
        if($upload_handle){
            if($this->userRepo->insertTableValue('users',$input)) return redirect()->route('admin.user.list')->with('success', 'Create User Complete');

            else return redirect()->route('admin.user.list')->with('error', 'Create User Failed');
        }
        else return redirect()->route('admin.list')->with('error', trans('form.upload_fail'));

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
    public function edit($user_id, UserRepository $userRepo)
    {
    	$data['user'] = (array)$this->userRepo->getUserbyID($user_id);
        if(!empty($data['user']['avatar'])){
            $data['user']['old_avatar'] = url('storage'.config('constant.image.user').'/'.$data['user']['avatar']);
        }
    	return $this->render('backend.user.create_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($user_id, UpdateUser $request)
    {
    	$input = $request->only('name', 'address','website','phone','status','avatar');
        if(!isset($input['status'])) $input['status'] = 0;
        $input['birthday'] = Carbon::create($request->input('birthday'))->toDateString();
        $input['slug'] = $this->slugService->createSlug('users', $request->name, $user_id);
        $upload_handle = true;
        if($request->hasFile('avatar')){
            $avatar = $request->file('avatar');
            $newname = $input['slug'].'_'.time().'.'.$avatar->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($avatar, config('constant.image.user'), $newname,'yes','no');
            $input['avatar'] = $newname;

            //remove old avatar
            if($upload_handle )$this->imageService->removeOldImage(config('constant.image.user'), $this->getImageNamefromLink($request->old_avatar));
        }
        if($this->userRepo->updateUser($user_id, $input)) return redirect()->route('admin.user.list')->with('success', 'Update User Complete');
        else return redirect()->route('admin.user.list')->with('error', 'Update User Failed');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($user_id, $option)
    {
        if($this->userRepo->destroy($user_id, $option))
        {
            return redirect()->route('admin.user.list')->with('success', trans('form.delete_success') );
        }
        else return redirect()->route('admin.list')->with('error', trans('form.delete_fail'));
    }
    public function restore($user_id)
    {
        if($this->userRepo->restore($user_id))
        {
            return redirect()->route('admin.user.list')->with('success', trans('form.restore_success'));
        }
        else return redirect()->route('admin.user.list')->with('error', trans('form.restore_fail'));
    }
}
