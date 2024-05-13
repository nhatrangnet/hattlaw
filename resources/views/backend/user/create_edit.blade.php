<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i> {{ empty($user['id'])?trans('form.create'):trans('form.edit') }} User</h5><hr>
    </div>
    <div class="block-form">
    	@if(empty($user['id']))
		{!!Form::open(['route'=>'admin.user.store','name' => 'frm','id'=>'user-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data'  ]) !!}
		@else
		{!! Form::model($user, ['method' => 'PATCH', 'action' => ['Backend\UserController@update', $user['id']],'id'=>'user-create-form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
			{{Form::hidden('old_avatar', $user['old_avatar']??'')}}
		@endif

			<div class="form-row">
				<div class="col-md-6">
					<div class="form-row">
						<div class="col-md-6">
							{{ Form::form_text('name', $user['name']??'',['id' => 'name', 'class' => 'form-control required' ]) }}
						</div>
						<div class="col-md-6">
							{{ Form::form_text('website', $user['website']??'',['id' => 'website', 'class' => 'form-control' ]) }}
						</div>
					</div>
					
				</div>
				<div class="col-md-6 mb-2">
					@if(empty($user['id']))
					{{ Form::form_email('email', $user['email']??'',['id' => 'email', 'class' => 'form-control' ]) }}
					@else
					{{ Form::form_email('email', $user['email']??'',['id' => 'email', 'class' => 'form-control','disabled' => 1 ]) }}
					@endif
				</div>
			</div>

			<div class="form-row">
				<div class="col-md-6 mb-2">
					{{ Form::form_text('address',$user['address']??'',['id' => 'address', 'class' => 'form-control' ]) }}
					{{ Form::form_text('phone',$user['phone']??'',['id' => 'phone', 'class' => 'form-control' ]) }}

                    {{ Form::label(trans("form.birthday"), null, ['class' => 'control-label']) }}
					<b>{{$user['birthday']??''}}</b>
                    <div class="input-group date" id="birthday" data-target-input="nearest">
                        {{ Form::form_time_search('birthday', '',['placeholder' => "dd-mm-yyyy",'class' => 'form-control datetimepicker-input' ]) }}
                        <div class="input-group-append" data-target="#birthday" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>

					{{ Form::form_status('status',$user['status']??'',['1' => config('constant.form.active'), '0' => config('constant.form.inactive')], ['id' => 'status', 'class' => 'form-control required' ]) }}
				</div>
				<div class="col-md-6 mb-2">
					<div class="card">
						<div class="card-header">
							{{ Form::form_file('avatar',['id' => 'avatar', 'class' => 'form-control' ]) }}
						</div>
						<div class="card-body text-center">
							<img src="{{ $user['old_avatar']??url('storage/basic/no_image.png') }}" class="img-fluid" alt="image">
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="form-row">
				<div class="mx-auto">
					@if(!empty($user['id']))
					<a onclick="return delete_confirm()" href="{{route('admin.user.destroy',[$user['id'], 'force'])}}" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt">{{trans('form.force_delete') }}</i></a>
					@endif
				</div>
			</div><hr>
			{{ Form::submit_back_button() }}
		{!! Form::close() !!}
	</div>
</div>
