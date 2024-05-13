<div class="block">
	<div class="block-title">
		<h5><i class="fa fa-edit"></i> {{ empty($order['id'])?trans('form.create'):trans('form.edit') }} {{ trans('menu.order.name') }}</h5><hr>
	</div>

	<div class="block-form">
		@if(empty($order['id']))
		{!!Form::open(['route'=>'cart.store','name' => 'frm','id'=>'order-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
		@else
		{!! Form::model($order, ['method' => 'PATCH', 'action' => ['Frontend\CartController@update', $order['id']],'id'=>'order-create-form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data']) !!}
		@endif

		<div class="form-row">
			<div class="col-md-6">
				{{ Form::label(trans("form.list").' '.trans("form.customer"), null, ['class' => 'control-label']) }}
				@if(empty($order['id']))
				{{ Form::select('user_id', $customer_list??'', 0, array_merge(['id' => 'customer_list', 'class' => 'custom-select'] )) }}
				@endif
				<hr>
				<div class="form-row old_user d-none">
				{{ Form::checkbox('old_user_chkbox', 1, true, ['id' => 'old_user_chkbox']) }}<i>{{trans('frontend.old_customer')}}</i>
				</div>
				<div class="form-row">
					
					<div class="col-md-6">
						{{ Form::form_text('name', $order['name']??'',['id' => 'name', 'class' => 'form-control required' ]) }}
					</div>
					<div class="col-md-6">
						{{ Form::form_text('phone', $order['phone']??'',['id' => 'phone', 'class' => 'form-control ' ]) }}
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-8">
						{{ Form::form_text('address', $order['address']??'',['id' => 'address', 'class' => 'form-control ' ]) }}
					</div>
					<div class="col-md-4">
						{{ Form::form_email('email', $order['email']??'',['id' => 'email', 'class' => 'form-control ' ]) }}
					</div>

				</div>
				<hr>
				<label for="note">{{ trans('form.note') }}</label>
				{{ Form::textarea('note',$order['note']??'',['id' => 'note', 'class' => 'form-control', 'rows' => 4 ]) }}
			</div>
			<div class="col-md-6">
				@if(empty($order['id']))
				{{ Form::label(trans("form.nhanvien"), null, ['class' => 'control-label']) }}
            	{{ Form::form_select('nhanvien', $nhanvien_list, 0, ['class' => 'form-control1 required']) }}
            	<hr>
				<div class="form-row">
					<div class="swiper-container slide_multi">
						<div class="swiper-wrapper">
							@if(!empty($product_list))
							@foreach($product_list as $id => $product)
							<div class="swiper-slide">
								<div class="post-slide2">
									<div class="post-img d-none">
										<img src="{{$product['image']}}" alt="">
									</div>
									<div class="post-content p-0 p-md-3">
										{{$product['name']}} <i class="fas fa-store float-right"> {{$product['quantity']}}</i>
										<div class="justify-content-center form-row">
											<button id="add_to_cart" data-product-id="{{ $id }}" class="btn btn-sm btn-info"><i class="fas fa-cart-plus red"></i>{{trans('form.add_to_cart')}}</button>
										</div>

									</div>
								</div>
							</div>
							@endforeach
							@endif
						</div>
						<div class="swiper-pagination"></div>
						<div class="swiper-button-prev"></div>
						<div class="swiper-button-next"></div>
						<div class="swiper-scrollbar"></div>
					</div>
				</div>
				
				<div class="form-row" id="cart_detail"></div>
				@else
				<div class="table">
					<b class="mr-1">{{trans("form.status")}}:  </b>
					@if($order['status'] == 2)
						<button type="button" class="btn btn-primary btn-sm"><i class="fas fa-clipboard-check mr-1"></i><span class="red">{{trans("form.order_success")}}</span></button>
					@elseif($order['status'] == 3)
						<button type="button" class="btn btn-danger btn-sm"><i class="far fa-calendar-times mr-1"></i><span class="red">{{trans("form.order_fail")}}</span></button>
					@elseif($order['status'] == 1)
						<button type="button" class="btn btn-info btn-sm"><i class="fas fa-motorcycle"></i><span class="red">{{trans("form.order_proccess")}}</span></button>
					@endif

					<p><b>{{trans("form.nhanvien")}}: <span class="red">{{$nhanvien}}</span></b></p>
				</div>
				<div class="table table-responsive text-center">
					<table class="table table-bordered table-striped table-hover table-sm">
						<tbody>
							<tr>
								<th>{{trans('form.name')}}</th>
								<th>{{trans('form.price')}}</th>
								<th>{{trans('form.quantity')}}</th>
								<th>{{trans('form.total')}}</th>
							</tr>
							@if(!empty($items) && count($items) > 0)
							@foreach($items as $product)
							<tr>
								<th>{{$product->name}}</th>
								<th>{{number_format($product->price)}}</th>
								<th>{{$product->quantity}}</th>
								<th>{{number_format($product->price * $product->quantity)}}{{trans('form.money_symbol')}}</th>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
				</div>
				
				<div class="row w-100">
					<div class="d-inline col-6 text-right">{{trans('form.subtotal')}}</div>
					<div class="d-inline col-6 text-right">{{number_format($order['subtotal']??0)}}{{trans('form.money_symbol')}}</div>

					<div class="d-inline col-6 text-right mt-1">{{trans('form.shipping_charge')}} <i>({{trans('form.money_symbol')}})</i></div>
					<div class="d-inline col-6 text-right mt-1">+ {{number_format($order['shipping_charge']??0)}}{{trans('form.money_symbol')}}</div>

					<div class="d-inline col-6 text-right mt-1">{{trans('form.tax')}} <i>({{$order['tax']}}%)</i></div>
					
					<div class="d-inline col-6 mt-1 text-right">+ {{number_format(($order['subtotal']*$order['tax'])/100)}}{{trans('form.money_symbol')}}</div>

					<div class="d-inline col-6 text-right mt-1">{{trans('form.discount')}} <i>({{$order['discount_percent']}}%)</i></div>
					<div class="d-inline col-6 mt-1 text-right">- {{number_format(($order['subtotal']*$order['discount_percent'])/100)}}{{trans('form.money_symbol')}}</div>
					<br>
					<div class="d-inline col-6 text-right"><b>{{trans('form.total')}}</b></div>
					<div class="d-inline col-6 text-right"><b>{{number_format(countTotal($order['subtotal']??null, $order['shipping_charge']??null, $order['tax']??null, $order['discount_percent']??null, $order['discount']??null))}}{{trans('form.money_symbol')}}</b></div>
				</div>
				@endif
				
			</div>
		</div>
		<div class="col-md-12 text-center mt-2">
			@if(empty($order['id']))
			<button type="submit" class="btn btn-primary" id="submit-button">{{trans('form.submit')}}</button>
			@endif
			<a class="btn btn-info" href="{{ route('cart.index') }}">{{trans('form.back')}}</a>
			@if(!empty($order['id']) && $order['status'] != 3)
			<a href="{{ route('print.order',$order['id']) }}" class="btn btn-success" data-order-id="{{$order['id']}}"><i class="fas fa-print"></i> {{trans('form.print')}}</a>
			@endif
		</div>
		{!! Form::close() !!}
	</div>
</div>