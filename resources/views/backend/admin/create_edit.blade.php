<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i> {{ empty($admin['id'])?trans('form.create'):trans('form.edit') }} {{trans('form.admin')}}</h5><hr>
    </div>
    <div class="block-form">
  	@if(empty($admin['id']))
		{!!Form::open(['route'=>'admin.store','name' => 'frm','id'=>'user-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
		@else
		{!! Form::model($admin, ['method' => 'PATCH', 'action' => ['Backend\DashboardController@update', $admin['id'] ],'id'=>'user-create-form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data']) !!}
			{{ Form::hidden('old_avatar', $admin['avatar']??'') }}
			{{ Form::hidden('old_image', $admin['image']??'') }}
			{{ Form::hidden('old_roles', json_encode($admin['roles']??'')) }}
		@endif
		
		@csrf
		
			<div class="form-row">
				<div class="col-md-6 mb-2">
					
					<div class="row">
						<div class="col-12 col-md-6">
							{{ Form::form_text('name', $admin['name']??'',['id' => 'name', 'class' => 'form-control required' ]) }}
						</div>
						<div class="col-12 col-md-6">
							{{ Form::form_status('status',$admin['status']??'',[]) }}
						</div>
					</div>
					
					{{ Form::label('name', null, ['class' => 'control-label']) }}
					{{ Form::text('en_name', $admin['en_name']??'',['class' => 'form-control' ]) }}

					<div class="row">
						<div class="col-12 col-md-6">
							{{ Form::label( 'Chức vụ', null, ['class' => 'control-label']) }}
							{{ Form::text('metadata[position][vi]',$metadata['position']['vi']??'',['class' => 'form-control' ]) }}
						</div>
						<div class="col-12 col-md-6">
							{{ Form::label( 'Position', null, ['class' => 'control-label']) }}
							{{ Form::text('metadata[position][en]',$metadata['position']['en']??'',['class' => 'form-control' ]) }}
						</div>
					</div>
					
					<div class="row">
						<div class="col-12 col-md-6">
							{{ Form::form_text('phone',$admin['phone']??'',['class' => 'form-control' ]) }}
						</div>
						<div class="col-12 col-md-6">
							{{ Form::form_email('email', $admin['email']??'',['class' => 'form-control' ]) }}
						</div>
					</div>
					
					
					<hr>

					<div class="row">
						<div class="col-12 col-md-6">
							<div class="card">
								<div class="card-header">
									{{ Form::form_file('avatar',['class' => 'form-control' ]) }}
								</div>
								<div class="card-body text-center">
									<img src="{{ $admin['old_avatar']??'' }}" alt="avatar" class="img-fluid">
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="card">
								<div class="card-header">
									{{ Form::form_file('image',['class' => 'form-control' ]) }}
								</div>
								<div class="card-body text-center">
									<img src="{{ $admin['old_image']??'' }}" alt="image" class="img-fluid">
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="col-md-6 mb-2">
					{{ Form::label( trans('form.description'), null, ['class' => 'control-label']) }}
					{{ Form::textarea('description',$admin['description']??'',['class' => 'summernote form-control require' ]) }}
					<br>
					{{ Form::label('description', null, ['class' => 'control-label']) }}
					{{ Form::textarea('en_description',$admin['en_description']??'',['class' => 'summernote form-control' ]) }}

					<label for="category_id">{{ trans('form.role') }} </label>
					<select name="role_ids[]" id="multi_select_row" class="form-control"  multiple="multiple">
						@foreach($listRole as $key => $val)
							<option value="{{ $key }}" {{ array_key_exists($key, $admin['roles'])?'selected':'' }} >{{ $val }}</option>
					  	@endforeach
					</select>

				</div>
			</div>

		
			{{-- @if(empty($admin['id']))
			<div class="form-row">
				<div class="col-md-6 mb-2">
					{{ Form::form_password('password',['id' => 'password', 'class' => 'form-control required' ]) }}
				</div>
				<div class="col-md-6 mb-2">
					{{ Form::form_password('password_confirmation',['id' => 'password-confirm', 'class' => 'form-control required' ]) }}
				</div>
			</div>
			@endif --}}
		

			{{-- <div class="form-row">
				<div class="mx-auto">
					@if(!empty($admin['id']) && !array_key_exists(1,$admin['roles']))
					<a onclick="return delete_confirm()" href="{{route('admin.destroy',[$admin['id'], 'force'])}}" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt">{{trans('form.force_delete') }}</i></a>
					@endif
				</div>
			</div> --}}
			<hr>
			{{ Form::submit_back_button() }}
		{!! Form::close() !!}
	</div>
</div>