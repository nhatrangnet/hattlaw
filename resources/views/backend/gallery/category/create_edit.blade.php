<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i> {{ empty($category['id'])?trans('form.create'):trans('form.edit') }} gallery</h5><hr>
    </div>

    <div class="block-form">
    	@if(empty($category['id']))
		{!!Form::open(['route'=>'admin.gallery_category.store','name' => 'frm','id'=>'user-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
		@else
		{!! Form::model($category, ['method' => 'PATCH', 'action' => ['Backend\Gallery\CategoryController@update', $category['id'] ],'id'=>'gallery-category-create-form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data']) !!}
		@endif
		
		@csrf
			<div class="form-row ">
				{{ Form::form_text('name', $category['name']??'',['id' => 'name', 'class' => 'form-control required' ]) }}
			</div>
            <div class="form-row">
                {{ Form::form_status('status',$category['status']??'',[]) }}                
            </div>
            <div class="card">
				<div class="card-header">
					{{ Form::form_file('cover',['id' => 'cover', 'class' => 'form-file' ]) }}
				</div>
				<div class="card-body text-center">
					<img src="{{ $category['old_image']?? url('images/basic/no_image.png') }}" class="img-fluid" alt="cover">
				</div>
			</div>
			<br>
			<div class="form-row">
				<div class="mx-auto">
					@if(!empty($category['id']))
					<a onclick="return delete_confirm()" href="{{route('admin.role.destroy',[$category['id'], 'force'])}}" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt">{{trans('form.force_delete') }}</i></a>
					@endif
				</div>
			</div><hr>
			{{ Form::submit_back_button() }}
		{!! Form::close() !!}
	</div>
</div>