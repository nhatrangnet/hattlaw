<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" id="viet-tab" data-toggle="tab" href="#viet" role="tab" aria-controls="viet" aria-selected="true">Viet</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="english-tab" data-toggle="tab" href="#english" role="tab" aria-controls="english" aria-selected="false">English</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" id="image-tab" data-toggle="tab" href="#image" role="tab" aria-controls="english" aria-selected="false">Image</a>
	</li>
</ul>
<div class="tab-content" id="myTabContent">
	<div class="tab-pane fade show active" id="viet" role="tabpanel" aria-labelledby="viet-tab">
		<div class="block">
			<div class="block-title">
				<h5><i class="fa fa-search"></i> {{ empty($service['id'])?trans('form.create'):trans('form.edit') }} Service</h5><hr>
			</div>
			<div class="block-form">
				@if(empty($service['id']))
				{!!Form::open(['route'=>'admin.service.store','name' => 'frm','id'=>'service-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data'  ]) !!}
				@else
				{!! Form::model($service, ['method' => 'PATCH', 'action' => ['Backend\ServiceController@update', $service['id']],'id'=>'service-create-form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
				@endif

				<div class="form-row">
					<div class="col-md-6 mb-2">
						{{ Form::form_text('name', $service['name']??'',['class' => 'form-control required' ]) }}

						{{ Form::label(trans('form.parent_category'), null, ['class' => 'control-label']) }}
						{{ Form::form_select('parent_id', $parent_service, $service['parent_id']??0, ['class' => 'form-control']) }}
							
						{{ Form::form_status('status',$service['status']??'',['1' => config('constant.form.active'), '0' => config('constant.form.inactive')], ['id' => 'status', 'class' => 'form-control required' ]) }}
					</div>
					<div class="col-md-6 mb-2">
						{{ Form::form_text('metakey', $service['metakey']??'',['class' => 'form-control' ]) }}

						<label for="Meta keywords" class="control-label">Meta Robot</label>
						{{ Form::form_select('metarobot', metarobot(), $service['metarobot']??'index, follow', ['id' => 'metarobot']) }}
						{{ Form::form_text('metades', $service['metades']??'',['class' => 'form-control' ]) }}
					</div>
				</div>


				<div class="form-group">
					<label for="description">{{ trans('form.description') }}</label>
					{{ Form::textarea('description',$service['description']??'',['class' => ' summernote form-control' ]) }}				
				</div>
				<hr>
				<div class="form-row">
					<div class="mx-auto">
						@if(!empty($service['id']))
						<a onclick="return delete_confirm()" href="{{route('admin.service.destroy',[$service['id'], 'force'])}}" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt">{{trans('form.force_delete') }}</i></a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="english-tab">
		<div class="block">
			<div class="block-title">
				<h5><i class="fa fa-search"></i> {{ empty($service['id'])?'Create ':'Edit ' }} Service</h5><hr>
			</div>
			<div class="block-form">
				

				<div class="form-row">
					<div class="col-md-6 mb-2">
						{{ Form::label('Name', null, ['class' => 'control-label']) }}
						{{ Form::text('en_name', $service['en_name']??'',['class' => 'form-control required' ]) }}
						
					</div>
					<div class="col-md-6 mb-2">
						{{ Form::label('Meta key', null, ['class' => 'control-label']) }}
						{{ Form::text('en_metakey', $service['en_metakey']??'',['class' => 'form-control' ]) }}

						<label for="Meta keywords" class="control-label">Meta Robot</label>
						{{ Form::select('en_metarobot', metarobot(), $service['en_metarobot']??'index, follow', ['class' => 'form-control']) }}

						{{ Form::label('Meta description', null, ['class' => 'control-label']) }}
						{{ Form::text('en_metades', $service['en_metades']??'',['class' => 'form-control' ]) }}
					</div>
				</div>


				<div class="form-group">
					<label for="description">{{ trans('form.description') }}</label>
					{{ Form::textarea('en_description',$service['en_description']??'',['class' => ' summernote form-control' ]) }}				
				</div>
				
				
			</div>
		</div>
	</div>
	<div class="tab-pane fade" id="image" role="tabpanel" aria-labelledby="image-tab">
		<div class="row">
			<div class="card col-12 col-md-6">
				<div class="card-header">
					{{ Form::form_file('image',['id' => 'image_slide', 'class' => 'form-file' ]) }}
				</div>
				<div class="card-body text-center">
					<img src="{{ !empty($service['image'])?\Storage::url(config('constant.image.service')."/".$service['image']):\Storage::url(config('constant.no-image')) }}" class="img-fluid" alt="slide-image">
				</div>
			</div>

			<div class="card col-12 col-md-6">
				<div class="card-header">
					{{ Form::form_file('cover',['class' => 'form-file' ]) }}
				</div>
				<div class="card-body text-center">
					<img src="{{ !empty($service['cover'])?\Storage::url(config('constant.image.service')."/".$service['cover']):\Storage::url(config('constant.no-image')) }}" class="img-fluid" alt="cover-image">
				</div>
			</div>
		</div>
	</div>
	{{ Form::submit_back_button() }}
	{!! Form::close() !!}
</div>
