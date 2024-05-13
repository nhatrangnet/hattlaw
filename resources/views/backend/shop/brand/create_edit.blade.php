<div class="block">
    <div class="block-title">
      <h5>{{ empty($brand['id'])?trans('form.create'):trans('form.edit') }} {{ trans('menu.brand.name') }}</h5><hr>
    </div>
    <div class="block-form">
    	@if(empty($brand['id']))
		{!!Form::open(['route'=>'admin.brand.store','name' => 'frm','id'=>'brand-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
		@else
		{!! Form::model($brand, ['method' => 'PATCH', 'action' => ['Backend\Shop\BrandController@update', $brand['id']],'id'=>'brand-create-form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data']) !!}
		{{-- {{ Form::hidden('id', mahoa_giaima('mahoa',$brand['id'])) }} --}}
		{{ Form::hidden('old_cover', $brand['cover']??'no_image') }}
		@endif
		
		{{-- @csrf --}}
			<div class="form-row">
				<div class="col-md-6 mb-2">
					{{ Form::form_text('name', $brand['name']??'',['id' => 'name', 'class' => 'form-control required' ]) }}
				</div>
				<div class="col-md-6 mb-2">
					{{ Form::form_status('status',$brand['status']??'',['1' => trans('form.active'), '0' => trans('form.inactive')], ['id' => 'status', 'class' => 'form-control required' ]) }}
				</div>
			</div>
			<div class="form-group">
				{{-- <div class="col-md-6 mb-2"> --}}
					<label for="description">{{ trans('form.description') }}</label>
					{{ Form::textarea('description',$brand['description']??'',['id' => 'description', 'class' => ' summernote form-control' ]) }}
				{{-- </div> --}}
				
			</div>

			<div class="form-row">
				<div class="col-md-6 mb-2">
					<div class="card">
						<div class="card-header">
							{{ Form::form_file('cover',['id' => 'cover', 'class' => 'form-file' ]) }}
						</div>
						<div class="card-body text-center">
							<img src="{{ $brand['old_cover']?? url('images/basic/no_image.png') }}" class="img-fluid" alt="cover">
						</div>
						
					</div>
				</div>
			</div><hr>

			{{ Form::submit_back_button() }}
		{!! Form::close() !!}
	</div>
</div>