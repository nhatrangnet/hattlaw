<div class="block">
	<div class="block-title">
		<h5><i class="fa fa-edit"></i> {{ empty($product['id'])?trans('form.create'):trans('form.edit') }} {{ trans('menu.product.name') }}</h5><hr>
	</div>
	

	<div class="block-form">
		@if(empty($product['id']))
		{!!Form::open(['route'=>'admin.product.store','name' => 'frm','id'=>'product-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
		@else
		{!! Form::model($product, ['method' => 'PATCH', 'action' => ['Backend\Shop\ProductController@update', $product['id']],'id'=>'product-create-form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data']) !!}
		{{ Form::hidden('id', mahoa_giaima('mahoa',$product['id'])) }}
		{{ Form::hidden('old_image', json_encode($old_image)) }}
		{{ Form::hidden('old_brand', json_encode($old_brand)) }}
		{{ Form::hidden('old_category', json_encode($old_category)) }}
		@endif

		<div class="form-row">
			<div class="col-md-6">
				<div class="form-row">
					<div class="col-md-6">
					{{ Form::form_text('name', $product['name']??'',['id' => 'name', 'class' => 'form-control required' ]) }}
					</div>
					<div class="col-md-6">
					{{ Form::form_text('sku', $product['sku']??'',['id' => 'sku', 'class' => 'form-control ' ]) }}
					</div>
				</div>
				
				<label for="description">{{ trans('form.description') }}</label>
				{{ Form::textarea('description',$product['description']??'',['id' => 'description', 'class' => 'summernote form-control' ]) }}
				<br>
				<div class="card">
					<div class="card-header multi_image_block" data-name="image">
						{{ Form::label('image', null, ['class' => 'control-label']) }}
						<button class="btn btn-info add_image_btn float-right">{{ trans('form.add_more') }}</button>
						<hr>
					</div>
					<div class="card-body text-center multi_image_old">
						<div class="row">
						@if(!empty($old_image))
						@foreach($old_image as $key => $link)
							<div class="col-lg-4 col-md-4 col-sm-4 col-6 product_image position-relative border-primary btn mb-2">
								<button type="button" class="btn mb-2 d-inline minh-150" data-toggle="modal" data-target="#{{$key}}"><img src="{{$link}}" class="img-thumbnail"></button>
								@if(strpos($link, 'no_image') == false ))
								<a onclick="return delete_confirm()" href="{{route('admin.delete_product_image',[$product['id'], str_replace("/", '&', $link)])}}" class="position-absolute fixed-bottom btn-lg delete_confirm product_image_delete_btn"><i class="fa fa-trash-alt btn btn-xs btn-danger"></i></a>
				                @endif

				                <div class="modal fade" id="{{$key}}" tabindex="-1" role="dialog">
								  <div class="product_image_modal modal-dialog modal-dialog-centered" role="document">
								    <div class="modal-content">
								      <div class="modal-body">
								      	<img src="{{$link}}" class="img-fluid rounded">
								      </div>
								    </div>
								  </div>
								</div>
				            </div>
						@endforeach
						@endif
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				
				<div class="form-row">
					<div class="col-md-4">
					{{ Form::form_text('quantity', $product['quantity']??'',['id' => 'quantity', 'class' => 'form-control ' ]) }}
					</div>
					<div class="col-md-4">
					{{ Form::form_text('price', $product['price']??'',['id' => 'price', 'class' => 'form-control ' ]) }}
					</div>
					<div class="col-md-4">
					{{ Form::form_status('status',$product['status']??'',['1' => trans('form.active'), '0' => trans('form.inactive')], ['id' => 'status', 'class' => 'form-control required' ]) }}
					</div>
				</div>
				

				<label for="category_id" class="popup">{{ trans('form.category') }} <span class="font-italic small">{{ trans('form.press_ctrl_select_multi') }}</span></label>
				{{ Form::form_select_multiple('category_id[]', $category_list, $old_category??'', ['id' => 'category_id']) }}

				<label for="brand_id" class="popup">{{ trans('form.brand') }} <span class="font-italic small">{{ trans('form.press_ctrl_select_multi') }}</span></label>
				{{ Form::form_select_multiple('brand_id[]', $brand_list, $old_brand??'', ['id' => 'brand_id']) }}
				
				{{ Form::form_text('metakey', $product['metakey']??'',['id' => 'metakey', 'class' => 'form-control' ]) }}

				{{ Form::label(trans("form.metarobot"), null, ['class' => 'control-label']) }}
				{{ Form::form_select('metarobot', metarobot(), $product['metarobot']??'index, follow', ['id' => 'metarobot']) }}

			</div>
		</div>


		<div class="form-group">
			
			
		</div>
		{{-- @can('product.update')
		{{ Form::submit_back_button() }}
		@else
		{{ Form::back_button() }}
		@endif
 		--}}
 		{{ Form::submit_back_button() }}
		{!! Form::close() !!}
	</div>
</div>