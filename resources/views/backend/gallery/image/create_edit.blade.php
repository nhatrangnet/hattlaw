<div class="block">
<div class="block-title">
  <h5><i class="fa fa-search"></i> {{ empty($role['id'])?trans('form.create'):trans('form.edit') }} Gallery</h5><hr>
</div>

<div class="block-form">
	{!!Form::open(['route'=>'admin.galleryimage.store','name' => 'frm','id'=>'admin-galleryimage-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
		<div class="form-group">
			{{ Form::form_select('category', $category_list) }}
		</div>
		<div class="form-group">
			<div class="card">
				<div class="card-header multi_image_block" data-name="image">
					{{ Form::label('image', null, ['class' => 'control-label']) }}
					<button class="btn btn-info add_image_btn float-right">{{ trans('form.add_more') }}</button>
					<hr>
				</div>

			</div>
		</div>
		{{ Form::submit_back_button() }}
	{!! Form::close() !!}
</div>
</div>