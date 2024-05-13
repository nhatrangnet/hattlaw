<div class="table table-responsive text-center">
	<table class="table table-bordered table-striped table-hover table-sm">
		<tbody>
			<tr>
				<th style="min-width: 120px">{{trans('form.name')}}</th>
				<th>{{trans('form.price')}}</th>
				<th style="max-width: 40px">{{trans('form.quantity')}}</th>
				<th style="min-width: 130px">{{trans('form.total')}}</th>
				<th style="min-width: 130px"></th>
			</tr>
			@if(!empty($items) && count($items) > 0)
			@foreach($items as $product)
			
			<tr>
				<th>{{$product['name']}}</th>
				<th>
					{{ Form::text('price_'.$product['product_id'], $product['price']??0,['id' => 'price_'.$product['product_id'], 'class' => 'form-control text-right' ]) }}
				</th>
				<th>{{ Form::text('quantity_'.$product['product_id'], $product['quantity']??0,['id' => 'quantity_'.$product['product_id'], 'class' => 'form-control text-right' ]) }}</th>
				<th>{{number_format($product['price'] * $product['quantity'])}}{{trans('form.money_symbol')}}</th>
				<th>
					<button data-product-id="{{$product['product_id']}}" data-price="price_{{$product['product_id']}}" data-quantity="quantity_{{$product['product_id']}}" class="update_cart_product btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="{{trans('form.update')}}"><i class="fas fa-edit"></i></button>
					<button data-product-id="{{$product['product_id']}}" data-price="price_{{$product['product_id']}}" data-quantity="quantity_{{$product['product_id']}}" class="delete_cart_product btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="{{trans('form.delete')}}"><i class="far fa-trash-alt"></i></button>
				</th>
			</tr>
			
			@endforeach
			@endif
		</tbody>
	</table>
</div>

<div class="row w-100">
	<div class="d-inline col-6 text-right">{{trans('form.subtotal')}}</div>
	<div class="d-inline col-6 text-right">{{number_format($subtotal??0)}}{{trans('form.money_symbol')}}</div>
	
	<div class="d-inline col-6 text-right mt-1">{{trans('form.shipping_charge')}} <i>({{trans('form.money_symbol')}})</i></div>
	<div class="d-inline col-3 mb-1">
		{{ Form::text('shipping_charge', $shipping_charge??0,['id' => 'order_shipping_charge', 'class' => 'form-control text-right d-inline mw-70' ]) }}<button class="btn btn-sm btn-primary ml-1 btn_cart" id="btn_cart_shipping_charge">+</button>
	</div>
	<div class="d-inline col-3 text-right mt-1">+ {{number_format($shipping_charge??0)}}{{trans('form.money_symbol')}}</div>

	<div class="d-inline col-6 text-right mt-1">{{trans('form.tax')}} <i>(%)</i></div>
	<div class="d-inline col-3">
		{{ Form::text('tax', $tax??0,['id' => 'order_tax', 'class' => 'form-control text-right d-inline mw-70' ]) }}<button type="button" class="btn btn-sm btn-primary ml-1 btn_cart" id="btn_cart_tax">+</button>
	</div>
	<div class="d-inline col-3 mt-1 text-right">+ {{number_format(($subtotal*$tax)/100)}}{{trans('form.money_symbol')}}</div>

	<div class="d-inline col-6 text-right mt-1">{{trans('form.discount')}} <i>(%)</i></div>
	<div class="d-inline col-3">
		{{ Form::text('discount_percent', $discount_percent??0,['id' => 'order_discount_percent', 'class' => 'form-control text-right d-inline mw-70 mt-1' ]) }}<button type="button" class="btn btn-sm btn-danger ml-1 btn_cart" id="btn_cart_discount_percent">--</button>
	</div>
	<div class="d-inline col-3 mt-1 text-right">- {{number_format(($subtotal*$discount_percent)/100)}}{{trans('form.money_symbol')}}</div>
	<br>
	<div class="d-inline col-6 text-right"><b>{{trans('form.total')}}</b></div>
	<div class="d-inline col-6 text-right"><b>{{number_format(countTotal($subtotal??null, $shipping_charge??null, $tax??null, $discount_percent??null, $discount??null))}}{{trans('form.money_symbol')}}</b></div>
</div>