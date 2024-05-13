<?php
namespace App\Repositories;

use App\Repositories\Repositories as Repositories;
use App\Model\Category;
use App\Model\Product;
use App\Model\Brand;
use App\Model\Order;
use Illuminate\Support\Facades\Redis;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Carbon;
use DB, Session;
class ShopRepository extends Repositories{


    function createService($input){
        return Service::create($input);
    }
    function updateService($service_id, $input){
        return Service::whereId($service_id)->update($input);
    }

    function createProduct($input){
        try{
            if(!empty($input['category_id'])){
                $category_ids = $input['category_id'];
                unset($input['category_id']);
            }
            if(!empty($input['brand_id'])){
                $brand_ids = $input['brand_id'];
                unset($input['brand_id']);
            }
            if(!empty($input['images'])){
                $images = $input['images'];
                unset($input['images']);
            }
            
            $product = Product::create($input);

            //add product images
            if(!empty($images)){
                foreach($images as $image){
                    DB::table('product_images')->insert(['product_id' => $product->id, 'image' => $image]);
                }
            }

            if(!empty($brand_ids)) $this->update_product_brands($product, [],$brand_ids);
            if(!empty($category_ids))$this->update_product_categories($product, [],$category_ids);
            
            return true;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return false;
    }
    function updateProduct($product_id, $input){
        try{
            $old_brand = json_decode($input['old_brand'],true);
            $old_category = json_decode($input['old_category'],true);
            $category_ids = $input['category_id']??[];
            $brand_ids = $input['brand_id']??[];
            unset($input['old_brand']);
            unset($input['old_category']);
            unset($input['category_id']);
            unset($input['brand_id']);
            unset($input['images']);

            Product::withTrashed()->whereId($product_id)->update($input);

            //add product images
            if(!empty($input['images'])){
                foreach($input['images'] as $image){
                    DB::table('product_images')->insert(['product_id' => $product_id, 'image' => $image]);
                }
            }


            $product_model = Product::withTrashed()->find($product_id);
            $this->update_product_brands($product_model, $old_brand,[]);
            $this->update_product_categories($product_model, $old_category,[]);
            $this->update_product_brands($product_model, [],$brand_ids);
            $this->update_product_categories($product_model, [],$category_ids);

            return true;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return false;
    }
    function update_product_categories($product, $detach, $attach){
        try{
            // $product = Product::withTrashed()->find($product_id);
            $product->categories()->detach($detach);
            $product->categories()->attach($attach);
            return true;
        }
        catch (Exception $e)
        {
            echo 'Caught exception: '.  $e->getMessage()."\n";
        }
        return false;
    }

    function update_product_brands($product, $detach, $attach){
        try{
            $product->brands()->detach($detach);
            $product->brands()->attach($attach);
            return true;
        }
        catch (Exception $e)
        {
            echo 'Caught exception: '.  $e->getMessage()."\n";
        }
        return false;
    }
    function removeProductImage($product_id, $image)
    {
        try {
            return DB::table(config('constant.database.product_images'))->where('product_id', $product_id)->where('image', $image)->delete();
        } catch (Exception $e) {
            echo 'Caught exception: '.  $e->getMessage()."\n";
        }
    }
    function destroy($service_id, $option){
        try{
            $service = service::withTrashed()->findOrFail($service_id);
            $service->status = config('constant.status.off');
            $service->save();
            //xoa cac thong tin lien quan den service roi moi xoa service

            if($option == 'force'){
                //delete service avatar
                if($service->avatar != NULL )$this->imageService->removeOldImage(config('constant.image.service'), $service->avatar);

                return $service->forceDelete();
            }
            else return $service->delete();
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
    function restore($service_id){
        try{
            $admin = service::onlyTrashed()->whereId($service_id);
            $admin->restore();

            $admin = service::findOrFail($service_id);
            $admin->status = config('constant.status.on');
            return $admin->save();

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

	function getAllCategoryWithRequestEloquent($request){
		$query = Category::withTrashed()->select(['id', 'name','slug','description','cover','status', 'updated_at','deleted_at']);
        $date_search = getDateTimeToSearch($request->get('from_datetime'), $request->get('to_datetime'));
		if ($request->has('name') && $request->get('name') != '') {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }

        if ($request->has('from_datetime') && $request->get('from_datetime') != '') {
           $query->where('updated_at', '>=', $date_search['from']);
        }

        if ($request->has('to_datetime') && $request->get('to_datetime') != '') {
           $query->where('updated_at', '<=', $date_search['to']);
        }

		return $query;
	}

    function getListBrand($request){
        $query = Brand::select(['id', 'name','slug','description','cover','status', 'updated_at']);
        $date_search = getDateTimeToSearch($request->get('from_datetime'), $request->get('to_datetime'));
        if ($request->has('name') && $request->get('name') != '') {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }

        if ($request->has('from_datetime') && $request->get('from_datetime') != '') {
           $query->where('updated_at', '>=', $date_search['from']);
        }

        if ($request->has('to_datetime') && $request->get('to_datetime') != '') {
           $query->where('updated_at', '<=', $date_search['to']);
        }

        return $query;
    }

    function getAllProductWithRequestEloquent($request){
        $query = Product::withTrashed()->select('*');
        $date_search = getDateTimeToSearch($request->get('from_datetime'), $request->get('to_datetime'));
        if ($request->has('name') && $request->get('name') != '') {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }

        if ($request->has('from_datetime') && $request->get('from_datetime') != '') {
           $query->where('updated_at', '>=', $date_search['from']);
        }

        if ($request->has('to_datetime') && $request->get('to_datetime') != '') {
           $query->where('updated_at', '<=', $date_search['to']);
        }

        return $query;
    }

    public function getBrandList($value='')
    {
        return DB::table('brands')->where('status', config('constant.status.on'))->get();
    }

    public function getProductCategory($product_id)
    {
        return DB::table('category_product')->select('category_id')->where('product_id',$product_id)->get();
    }
    public function getProductImage($product_id)
    {
        return DB::table('product_images')->select('image')->where('product_id',$product_id)->get();
    }
    public function getFirstProductImage($product_id)
    {
        $result = DB::table('product_images')->select('image')->where('product_id',$product_id)->first();
        if(!empty($result)) return $result->image;
        return null;
    }
    public function getProductBrand($product_id)
    {
        return DB::table('brand_product')->select('brand_id')->where('product_id',$product_id)->get();
    }
    public function delete_product_image($product_id, $image)
    {
        try {
            if(!empty($image)){
                $image = str_replace('&', '/', $image);
                // return DB::table('product_images')->where('image','like',"%{$image}%")->delete();

                return true;
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function get_list_order_ajax($request)
    {
        $query = Order::withTrashed()->select('*');
        if ($request->has('name') && $request->get('name') != '') {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }

        if ($request->has('phone') && $request->get('phone') != '') {
            $query->where('phone', 'like', "%{$request->get('phone')}%");
        }
        if(!(empty($request->has('nhanvien'))) && $request->get('nhanvien') != 999) {
            $query->where('admin_id',"{$request->get('nhanvien')}");
        }
        if($request->has('time_type') && $request->has('report_time') && $request->get('time_type') == 0){
            if ($request->get('report_time') == 0){ //today
                $today = Carbon::now()->format('Y-m-d');
                $query->where('updated_at', 'like', "%{$today}%");
            }
            
            if($request->get('report_time') == 1){ //this week
                $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
                $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');

                $query->where('updated_at', '>=', $start_week.' 00:00:00');
                $query->where('updated_at', '<=', $end_week.' 23:59:59');
            }
            if($request->get('report_time') == 2){ //this month
                $start_month= Carbon::now()->startOfMonth()->format('Y-m-d');
                $end_month = Carbon::now()->endOfMonth()->format('Y-m-d');
                $query->where('updated_at', '>=', $start_month.' 00:00:00');
                $query->where('updated_at', '<=', $end_month.' 23:59:59');
            }
        }
        if($request->has('time_type') && $request->get('time_type') == 1){
            $date_search = getDateTimeToSearch($request->get('from_datetime'), $request->get('to_datetime'));

            if ($request->has('from_datetime') && $request->get('from_datetime') != '') {
               $query->where('updated_at', '>=', $date_search['from']);
            }

            if ($request->has('to_datetime') && $request->get('to_datetime') != '') {
               $query->where('updated_at', '<=', $date_search['to']);
            }
        }

        return $query->orderBy('created_at', 'DESC');
    }

    public function addToCart($product_id)
    {
        $order = session('order');
        

        if(array_key_exists($product_id, $order['items'])){
            $order['items'][$product_id]['quantity']++;
        }
        else{
            $product = $this->getbyID('products', $product_id);
            $order['items'][$product_id] = [
                'product_id' => $product_id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $this->getFirstProductImage($product_id),
                'quantity' => 1,
            ];
        }
        $order['subtotal']=0;
        foreach($order['items'] as $item){
            $order['subtotal'] += ($item['price']*$item['quantity']);
        }
        
        Session::put('order',$order);
        return true;
    }
    public function addShippingCharge($shipping_charge)
    {
        $order = session('order');
        $order['shipping_charge'] = $shipping_charge;
        Session::put('order',$order);
        return true;
    }
    public function addTax($tax)
    {
        $order = session('order');
        $order['tax'] = $tax;
        Session::put('order',$order);
        return true;
    }
    public function addDiscountPercent($discount_percent)
    {
        $order = session('order');
        $order['discount_percent'] = $discount_percent;
        Session::put('order',$order);
        return true;
    }

    public function addCouponToOrder()
    {
        
    }
    public function confirmOrder($input)
    {        
        unset($input['customer_list']);
        $order = session('order');
        if(empty($order['items'])) return false;
        if(isset($input['old_user_chkbox'])) $order_sql['user_id'] = $input['user_id'];
        $order_sql['admin_id'] = $input['nhanvien'];
        $order_sql['name'] = $input['name'];
        $order_sql['phone'] = $input['phone'];
        $order_sql['address'] = $input['address'];
        $order_sql['email'] = $input['email'];
        $order_sql['note'] = $input['note'];

        $order_sql['subtotal'] = $order['subtotal'];
        $order_sql['discount_percent'] = $order['discount_percent'];
        $order_sql['discount'] = $order['discount'];
        $order_sql['coupon_id'] = $order['coupon_id'];
        $order_sql['shipping_charge'] = $order['shipping_charge'];
        $order_sql['tax'] = $order['tax'];
        $order_sql['total'] = countTotal($order['subtotal'], $order['shipping_charge'], $order['tax'], $order['discount_percent'], $order['discount']);
        $order_sql['created_at'] = Carbon::now();
        $order_sql['updated_at'] = Carbon::now();
        $order_id = DB::table('orders')->insertGetId($order_sql);
        foreach($order['items'] as $product_id => $item){
            DB::table('order_product')->insert([
                'order_id' => $order_id,
                'product_id' => $product_id,
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'image' => $item['image'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('products')->where('id',$product_id)->decrement('quantity', $item['quantity']);
        }
        session()->forget('order');
        return true;
    }
    public function update_cart_product($request)
    {
        $order = session('order');

        if(array_key_exists($request['product_id'], $order['items'])){
            $order['items'][$request['product_id']]['quantity'] = $request['quantity'];
            $order['items'][$request['product_id']]['price'] = $request['price'];
            $order['subtotal']=0;
            foreach($order['items'] as $item){
                $order['subtotal'] += ($item['price']*$item['quantity']);
            }
            
            Session::put('order',$order);
            return true;
        }
        return false;
    }
    public function delete_cart_product($product_id)
    {
        $order = session('order');

        if(array_key_exists($product_id, $order['items'])){
            unset($order['items'][$product_id]);
            $order['subtotal']=0;
            foreach($order['items'] as $item){
                $order['subtotal'] += ($item['price']*$item['quantity']);
            }
            
            Session::put('order',$order);
            return true;
        }
        return false;
    }
    public function update_cart_status($input)
    {
        if($input['status'] == 2){ //order success
            return DB::table(config('constant.database.order'))->where('id',$input['order_id'])->update(['status' => 2, 'updated_at' =>  Carbon::now()]);
        }
        elseif($input['status'] == 3){ // order fail

            if(DB::table(config('constant.database.order'))->where('id',$input['order_id'])->update(['status' => 3, 'subtotal' => 0, 'tax' => 0, 'discount' => 0, 'discount_percent' => 0, 'shipping_charge' => 0, 'total' => 0, 'updated_at' =>  Carbon::now()])){
                
                //return product quantity
                $order_product = DB::table(config('constant.database.order_product'))->select('product_id','quantity')->where('order_id',$input['order_id'])->get();
                if(!empty($order_product)){
                    foreach($order_product as $val){
                        DB::table(config('constant.database.product'))->where('id', $val->product_id)->increment('quantity', $val->quantity);
                    }
                }
            }
        }
    }
    /*
        Nhap hang
    */
    public function createStore($input)
    {
        $this->insertTableValue('product_history',[
            'product_id' => $input['product'],
            'brand_id' => $input['brand_id'],
            'change_content' => $input['change_content'],
            'plus_quantity' => $input['quantity'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return DB::table('products')->where('id', $input['product'])->increment('quantity', $input['quantity']);
    }

    public function getExport($from = null, $to = null)
    {
        // SELECT DATE(updated_at), product_id, SUM(quantity) FROM order_product GROUP BY DATE(updated_at), product_id

        $query = DB::table('order_product')->select('product_id', DB::raw('DATE(updated_at) as sum_date'), DB::raw('SUM(quantity) as total') )->groupBy('product_id')->groupBy(DB::raw('DATE(updated_at)'));
        if(isset($from)){
            if(!isset($to)) $query->where(DB::raw('DATE(updated_at)'),'=',$from);
            else $query->where(DB::raw('DATE(updated_at)'),'>=',$from);
        }
        if(isset($to)) $query->where(DB::raw('DATE(updated_at)'),'<=',$to);
        return $query->orderBy('product_id', 'asc')->get();

    }
    public function getImport($from = null, $to=null)
    {
        $query = DB::table('product_history')->select('product_id', DB::raw('DATE(updated_at) as sum_date'), DB::raw('SUM(plus_quantity) as total') )->groupBy('product_id')->groupBy(DB::raw('DATE(updated_at)'));
        if(isset($from)){
            if(!isset($to)) $query->where(DB::raw('DATE(updated_at)'),'=',$from);
            else $query->where(DB::raw('DATE(updated_at)'),'>=',$from);
        }
        if(isset($to)) $query->where(DB::raw('DATE(updated_at)'),'<=',$to);
        return $query->orderBy('product_id', 'asc')->get();
    }
    public function getStatisticStore($request)
    {
        $return = [];
        $return['product_list'] = $this->getAllTableActive('products');
        if(isset($request['time_type']) && isset($request['report_time']) && $request['time_type'] == 0){

            if ($request['report_time'] == 0){ //today
                $today = Carbon::now()->format('Y-m-d');
                $return['export'] = $this->getExport($today);
                $return['import'] = $this->getImport($today);
            }
            if ($request['report_time'] == 1){ //this week
                $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
                $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');

                $return['export'] = $this->getExport($start_week, $end_week);
                $return['import'] = $this->getImport($start_week, $end_week);
            }
            if ($request['report_time'] == 2){ //this month
                $start_month= Carbon::now()->startOfMonth()->format('Y-m-d');
                $end_month = Carbon::now()->endOfMonth()->format('Y-m-d');

                $return['export'] = $this->getExport($start_month, $end_month);
                $return['import'] = $this->getImport($start_month, $end_month);
            }
        }
        if(isset($request['time_type']) && $request['time_type'] == 1){
            $date_search = getDateTimeToSearch($request['from_datetime'], $request['to_datetime']);
            $return['export'] = $this->getExport($date_search['from']??null, $date_search['to']??null);
            $return['import'] = $this->getImport($date_search['from']??null, $date_search['to']??null);
        }

        return json_decode(json_encode($return), true);
    }
    public function store_report_save($input)
    {
        try
        {
            foreach($input as $product_id => $quantity){
                DB::table(config('constant.database.product'))->where('id',$product_id)->update(['quantity' => $quantity, 'updated_at' =>  Carbon::now()]);
            }
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }
}
