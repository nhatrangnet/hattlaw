<h4>{{ trans('menu.store.report')}}: {{$date}}</h4>
{!!Form::open(array('url'=>'store_report_save','method' => 'post','name' => 'frm','class' => 'form-horizontal form-bordered form-inline form-admin disabled_multi_submit')) !!}
<div class="form-row1 align-items-center justify-content-center w-100">
	@foreach($product_list as $product)
	<div class="form-row">
		<label for="{{ $product['slug'] }}" class="col-sm-2 col-form-label">{{$product['name']}}</label>
		<div class="col-sm-10">
			<input type="text" name="{{$product['id']}}" class="form-control" id="{{$product['slug']}}" value="{{$product['quantity']}}" placeholder="{{trans('form.number')}}">
		</div>
	</div>
	@endforeach
	{{ Form::submit_button() }}
</div>
{!! Form::close() !!}