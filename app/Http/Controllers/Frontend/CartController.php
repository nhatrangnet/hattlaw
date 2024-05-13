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
use App\Repositories\AdminRepository;

use Intervention\Image\ImageManagerStatic as Image;
use App\Model\Admin;
use App\Model\Product;
use App\Model\Cart;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Carbon;
use URL, Input, Validator, HTML, Session, DB, Theme, File, PDF;

class CartController extends FrontendController
{
	private $shopRepository;
	private $userRepository;
    public function __construct()
    {
        parent::__construct();
        $this->shopRepository = new ShopRepository;
        $this->userRepository = new UserRepository;
        $this->adminRepository = new AdminRepository;

        $default = [
            'subtotal' => 0,
            'discount' => 0,
            'discount_percent' => 0,
            'coupon_id' => null,
            'shipping_charge' => 0,
            'tax' => 0,
            'items' => []
        ];
        Session::put('order',$default);
    }

    public function index()
    {
        $data=[];
        $list_nhanvien = $this->adminRepository->getAdminActiveByRole(config('constant.permissions.role.nhanvien'));
        if(!empty($list_nhanvien)){
            $data['list_nhanvien'][999] = trans('form.all_time');
            foreach ($list_nhanvien as $nhanvien) {
                $data['list_nhanvien'][$nhanvien->id] = $nhanvien->name;
            }
        }
        return $this->render('frontend.cart.index', $data);
    }
    public function printOrder($order_id)
    {
        $order = (array)$this->repository->getByID('orders', $order_id);
        $order['shipping_date'] = Carbon::now()->format(config('constant.DATE_TIME_FORMAT'));

        $order['items'] = $this->repository->getbyAttribute('order_product','order_id', $order_id)->get();
        
// echo '<pre>';print_r(bodau($order['name']));die;
        $pdf = PDF::loadView('pdf.cart', $order);
        return $pdf->download('invoice.pdf');
        
    }

    public function create()
    {
        $this->theme->asset()->serve('swiper');
        $product_list = $this->repository->getAllTableActive('products');
        if(count($product_list) > 0){
            foreach($product_list as $product){
                $images = $this->repository->getbyAttribute('product_images', 'product_id', $product->id)->get();
                $data['product_list'][$product->id]['name'] = $product->name;
                $data['product_list'][$product->id]['slug'] = $product->slug;
                $data['product_list'][$product->id]['quantity'] = $product->quantity;
                if(count($images) > 0){
                    $data['product_list'][$product->id]['image'] = url('storage'.config('constant.image.product').'/'.$images[0]->image);
                }
                else{
                    $data['product_list'][$product->id]['image'] = url('storage/basic/no_image.png');
                }
                
            }
        }
    	$data['customer_list'] = [];
    	$customer_list = $this->userRepository->getAllUserActive();
    	if(count($customer_list) > 0){
    		foreach($customer_list as $customer){
    			$data['customer_list'][$customer->id] = $customer->name;
    		}
    	}
        $nhanvien_list = $this->adminRepository->getAdminActiveByRole('nhanvien');
        if(count($nhanvien_list) > 0){
            foreach($nhanvien_list as $nhanvien){
                $data['nhanvien_list'][$nhanvien->id] = $nhanvien->name;
            }
        }
        echo '<pre>';print_r($data);die;
        return $this->render('frontend.cart.create_edit', $data);
    }
    public function store(Request $request)
    {
        
        if($this->shopRepository->confirmOrder($request->except('_token'))){
            // event(new SendEmail($order));

            //delete session
            session()->forget('order');
            return redirect()->route('cart.index')->with('success', trans('form.create_success'));
        }
        else return redirect()->route('cart.index')->with('error', trans('form.create_fail'));
    }
    public function addToCart(Request $request)
    {
        if(!empty($request->product_id)){
            $this->shopRepository->addToCart($request->product_id);
        }
        return $this->renderView('frontend.cart.cart_detail',session('order')??[] );
    }
    public function update_cart_product(Request $request)
    {

        if(!empty($request->product_id)){
            $this->shopRepository->update_cart_product($request->except('_token'));
        }
        return $this->renderView('frontend.cart.cart_detail',session('order')??[] );
    }
    public function delete_cart_product(Request $request)
    {
        if(!empty($request->product_id)){
            $this->shopRepository->delete_cart_product($request->product_id);
        }
        return $this->renderView('frontend.cart.cart_detail',session('order')??[] );
    }
    public function edit($order_id)
    {
        $this->theme->asset()->serve('swiper');
        
        $data['order'] = (array)$this->repository->getByID(config('constant.database.order'),$order_id);
        $data['items'] = $this->repository->getbyAttribute(config('constant.database.order_product'),'order_id',$order_id)->get();
        $data['nhanvien'] = '';
        if(!empty($data['order']['admin_id'])){
            $nhanvien = $this->repository->getbyAttribute(config('constant.database.admin'),'id',$data['order']['admin_id'])->first();
            $data['nhanvien'] = $nhanvien->name;
        }
        return $this->render('frontend.cart.create_edit', $data);
    }
    public function update()
    {
        
    }

    public function addShippingCharge(Request $request)
    {
        if(!empty($request->shipping_charge)){
            $this->shopRepository->addShippingCharge($request->shipping_charge);
        }
        return $this->renderView('frontend.cart.cart_detail',session('order')??[] );
    }
    public function addTax(Request $request)
    {
        if(!empty($request->tax)){
            $this->shopRepository->addTax($request->tax);
        }
        return $this->renderView('frontend.cart.cart_detail',session('order')??[] );
    }
    public function addDiscountPercent(Request $request)
    {
        if(!empty($request->discount_percent)){
            $this->shopRepository->addDiscountPercent($request->discount_percent);
        }
        return $this->renderView('frontend.cart.cart_detail',session('order')??[] );
    }
    
    public function show()
    {
        
    }
    public function change_customer_list_ajax(Request $request)
    {
        $customer = $this->repository->getbyID('users', $request->get('customer_id'));
        return [
            'name' => $customer->name,
            'phone' => $customer->phone,
            'address' => $customer->address,
            'email' => $customer->email,
        ];
    }
    public function get_list_order_ajax(Request $request)
    {
        $carts = $this->shopRepository->get_list_order_ajax($request);
        return Datatables::of($carts)
        ->addIndexColumn()
        ->editColumn('updated_at', function($object){
            return date("d-m-Y H:i:s", strtotime($object->updated_at));
        })
        ->editColumn('total', function($object){
            return number_format($object->total).trans('form.money_symbol');
        })
        ->editColumn('nhanvien', function($object){
            return Admin::name($object->admin_id);
        })
        ->editColumn('status', function($object){

            if($object->status==1) return '<button data-order-status="2" data-order-id="'.$object->id.'" class="btn btn-sm btn-primary mr-1 update_order_status" data-toggle="tooltip" title="'.trans('form.order_success').'"><i class="fas fa-clipboard-check"></i></button><button type="button" class="btn btn-success btn-sm">'.trans("form.order_proccess").'</button><button data-order-status="3" data-order-id="'.$object->id.'" class="btn btn-sm btn-danger ml-1 update_order_status" data-toggle="tooltip" title="'.trans('form.order_fail').'"><i class="far fa-calendar-times"></i></button>';
            if($object->status==2) return '<button type="button" class="btn btn-primary btn-sm"><i class="fas fa-clipboard-check mr-1"></i>'.trans("form.order_success").'</button>';
            if($object->status==3) return '<button type="button" class="btn btn-danger btn-sm"><i class="far fa-calendar-times mr-1"></i>'.trans("form.order_fail").'</button>';

        })
        ->addColumn('action', function ($object) {
            $return = '<a href="'.route('cart.edit',$object->id).'" class="btn btn-xs btn-primary mr-2" data-toggle="tooltip" data-placement="top" title="'.trans('form.detail').'"><i class="fa fa-edit"></i></a>';
            $return .= '<a href="'.route('print.order',$object->id).'" class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="'.trans('form.print').'"><i class="fa fa-print"></i></a>';
            return $return;
        })
        ->rawColumns(['action','status'])
        ->make(true);
    }
    public function update_cart_status(Request $request)
    {
        if(!empty($request->order_id)){
            $this->shopRepository->update_cart_status($request->all());
            return 1;
        }
        return 0;
    }
}
