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

use Intervention\Image\ImageManagerStatic as Image;
use App\Model\Admin;
use App\Model\Product;
use App\Model\Cart;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Carbon;
use URL, Input, Validator, HTML, Session, DB, Theme, File, PDF;

class StoreController extends FrontendController
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
    	$product_list = $this->repository->getAllTableActive('products');
        if(count($product_list) > 0){
            foreach($product_list as $product){
                $data['product_list'][$product->id] = $product->name;
                
            }
        }
        $brand_list = $this->repository->getAllTableActive('brands');
        if(count($brand_list) > 0){
            foreach($brand_list as $brand){
                $data['brand_list'][$brand->id] = $brand->name;
                
            }
        }
        return $this->render('frontend.store.index', $data);
    }

    public function get_list_product_ajax(Request $request)
    {

        $products = $this->shopRepository->getAllProductWithRequestEloquent($request);

        return Datatables::of($products)
        ->addIndexColumn()
        ->editColumn('status_data', function($object){
            return $object->status==1? '<button type="button" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>':'<button type="button" class="btn btn-secondary btn-sm"><i class="fa fa-times"></i></button>';
        })
        ->editColumn('updated_at', function($object){
            return date("d-m-Y H:i:s", strtotime($object->updated_at));
        })
        ->addColumn('action', function ($object){
            $return = '<a href="'.route('product.history',$object->id).'" class="btn btn-xs btn-primary mr-2"><i class="fa fa-edit"></i></a>';
            return $return;
        })
        ->rawColumns(['status_data','action'])
        ->make(true);
    }
    public function store(Request $request)
    {
    	// Nhập hàng
    	$input = $request->except('_token');
        $input['change_content'] = trans('menu.store.create').$input['quantity'];
        if($this->shopRepository->createStore($input)) return redirect()->route('store.index')->with('success', trans('form.create_success'));
        else return redirect()->route('store.index')->with('error', trans('form.create_fail'));
    }

    public function store_statistic()
    {
        return $this->render('frontend.store.statistic');
    }
    public function getStatisticStore(Request $request)
    {
        $statistic = $this->shopRepository->getStatisticStore($request->all());

        $data['statistic'] = [];
        if(!empty($statistic['import'])){
            foreach($statistic['import'] as $import){
                $data['statistic'][$import['sum_date']][$import['product_id']]['import'] = '+ '.$import['total'];
            }
        }
        if(!empty($statistic['export'])){
            foreach($statistic['export'] as $export){
                if(!array_key_exists($export['sum_date'], $data['statistic'])){
                    $data['statistic'][$export['sum_date']] = [];
                }
                $data['statistic'][$export['sum_date']][$export['product_id']]['export'] = '- '.$export['total'];
            }
        }
        if(!empty($statistic['product_list'])){
            foreach($statistic['product_list'] as $product){
                $data['product_list'][$product['id']]['name'] = $product['name'];
                $data['product_list'][$product['id']]['quantity'] = $product['quantity'];
            }
        }
        ksort($data['statistic']);
        foreach($data['statistic'] as $date => $val){
            foreach($data['product_list'] as $product_id => $val){
                $data['statistic'][$date][$product_id]['name'] = $val['name'];
                if(!isset($data['statistic'][$date][$product_id])){
                    $data['statistic'][$date][$product_id]['import'] = 0;
                    $data['statistic'][$date][$product_id]['export'] = 0;
                }
                if(!isset($data['statistic'][$date][$product_id]['import'])){
                    $data['statistic'][$date][$product_id]['import'] = 0;
                }
                if(!isset($data['statistic'][$date][$product_id]['export'])){
                    $data['statistic'][$date][$product_id]['export'] = 0;
                }
            }
            ksort($data['statistic'][$date]);
        }
        return $this->renderView('frontend.store.statistic_detail', $data);
    }

    public function store_report()
    {
        $data=[];
        $data = $this->shopRepository->getStatisticStore([]);
        $data['date'] = Carbon::now()->format('d-m-Y');
        return $this->render('frontend.store.report',$data);
    }
    public function store_report_save(Request $request)
    {
        if($this->shopRepository->store_report_save($request->except('_token'))) return redirect()->route('store.index')->with('success', trans('form.update_success'));
        else return redirect()->route('store.index')->with('error', trans('form.update_fail'));
    }
}
