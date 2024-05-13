<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i> {{ empty($category['id'])?trans('form.create'):trans('form.edit') }} {{ trans('menu.category.name') }}</h5><hr>
    </div>
    <div class="block-form">
    	@if(empty($category['id']))
		{!!Form::open(['route'=>'admin.blog_category.store','name' => 'frm','id'=>'category-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
		@else
		{!! Form::model($category, ['method' => 'PATCH', 'action' => ['Backend\Blog\CategoryController@update', $category['id']],'id'=>'category-create-form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data']) !!}
		{{ Form::hidden('id', mahoa_giaima('mahoa',$category['id'])) }}
		{{ Form::hidden('old_cover', $category['cover']??'no_image,') }}
		@endif
		
		{{-- @csrf --}}
			<div class="form-row">
				<div class="col-md-6 mb-2">
					{{ Form::form_text('name', $category['name']??'',['id' => 'name', 'class' => 'form-control required' ]) }}
				</div>
				<div class="col-md-6 mb-2">
				{{ Form::label(trans('form.category'), null, ['class' => 'control-label']) }}
				{{ Form::form_select('parent_id', $rootCategory_id, $category['parent_id']??'', ['id' => 'parent_id']) }}
				</div>
			</div>
			<div class="form-row">
				<div class="col-md-6 mb-2">
				{{ Form::form_text('metakey', $category['metakey']??'',['id' => 'metakey', 'class' => 'form-control' ]) }}
				</div>
				<div class="col-md-6 mb-2">
					{{ Form::form_select('metarobot', metarobot(), $category['metarobot']??'index, follow', ['id' => 'metarobot']) }}
				</div>
			</div>
			<div class="form-group">
				{{-- <div class="col-md-6 mb-2"> --}}
					<label for="description">{{ trans('form.description') }}</label>
					{{ Form::textarea('description',$category['description']??'',['id' => 'description', 'class' => ' summernote form-control' ]) }}
				{{-- </div> --}}
				
			</div>

			<div class="form-row">
				<div class="col-md-6 mb-2">
					{{ Form::form_status('status',$category['status']??'',['1' => trans('form.active'), '0' => trans('form.inactive')], ['id' => 'status', 'class' => 'form-control required' ]) }}
				</div>
				<div class="col-md-6 mb-2">
					<div class="card">
						<div class="card-header multi_image_block" data-name="cover">
							{{ Form::label('cover', null, ['class' => 'control-label']) }}
							<button class="btn btn-info add_image_btn float-right">{{ trans('form.add_more') }}</button>
							<hr>
						</div>
						<div class="card-body text-center multi_image_old">
							{!! $category['old_cover']??null !!}
						</div>
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="mx-auto">
					@if(!empty($category['id']))
					<a onclick="return delete_confirm()" href="{{route('admin.blog_category.destroy',[$category['id'], 'force'])}}" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt">{{trans('form.force_delete') }}</i></a>
					@endif
				</div>
			</div><hr>

			{{ Form::submit_back_button() }}
		{!! Form::close() !!}
	</div>
</div>