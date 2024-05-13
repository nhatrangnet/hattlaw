<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController as FrontendController;
use Modules\Admin\Http\Requests\RegisterAdminRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use App\Repositories\ShopRepository;
use App\Repositories\UserRepository;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;

use Intervention\Image\ImageManagerStatic as Image;
use App\Model\Admin;
use App\Model\Product;
use App\Model\Cart;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Carbon;
use URL, Input, Validator, HTML, Session, DB, Theme, File, PDF;

class UserController extends FrontendController
{
    private $shopRepository;
	private $userRepository;
    public function __construct()
    {
        parent::__construct();
        $this->shopRepository = new ShopRepository;
        $this->userRepository = new UserRepository;
    }

    public function index()
    {
        $data['time_birthday_search'] = timeStatisticSearch();
        return $this->render('frontend.user.index', $data);
    }

    public function get_list_user_ajax(Request $request)
    {
        $users = $this->userRepository->getAllUserWithRequestEloquent($request);

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
        ->addColumn('action', function ($user){
            $return = '<a href="'.route('admin.user.edit',$user->id).'" data-id="'.$user->id.'" class="btn btn-xs btn-primary mr-2 edit_user" data-toggle="modal" data-target="#edit_user_modal"><i class="fa fa-edit"></i></a>';
            return $return;
        })
        ->rawColumns(['status_data','action'])
        ->make(true);
    }
    public function store(Request $request)
    {
    	$input = $request->except('_token','birthday');
    	$input['birthday'] = Carbon::create($request->birthday)->toDateString();
        if($this->userRepository->createUser($input)) return redirect()->route('user.index')->with('success', trans('form.create_success'));
        else return redirect()->route('user.index')->with('error', trans('form.create_fail'));
    }
    public function edit($user_id)
    {
        $data['user'] = (array)$this->userRepository->getUserbyID($user_id);
        if(!empty($data['user']['avatar'])){
            $data['old_avatar'] = url('storage'.config('constant.image.user').'/'.$data['user']['avatar']);
        }
        return $this->renderView('frontend.user.create_edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update_user(Request $request)
    {
        $input = $request->except('_token','admin_id');
        if(!isset($input['status'])) $input['status'] = 1;
        $input['birthday'] = Carbon::create($request->input('birthday'))->toDateString();
        $input['slug'] = $this->slugService->createSlug('users', $request->name, $request['admin_id']);
        $upload_handle = true;
        if($request->hasFile('avatar')){
            $avatar = $request->file('avatar');
            $newname = $input['slug'].'_'.time().'.'.$avatar->getClientOriginalExtension();
            $upload_handle = $this->imageService->upload_image($avatar, config('constant.image.user'), $newname,'yes','no');
            $input['avatar'] = $newname;

            //remove old avatar
            if($upload_handle )$this->imageService->removeOldImage(config('constant.image.user'), $this->getImageNamefromLink($request->old_avatar));
        }

        if($this->userRepository->updateUser($request['admin_id'], $input)) return redirect()->route('user.index')->with('success', 'Update User Complete');
        else return redirect()->route('user.index')->with('error', 'Update User Failed');
    }
}
