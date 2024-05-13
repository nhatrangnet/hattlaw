<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i> {{ empty($role['id'])?trans('form.create'):trans('form.edit') }} {{trans('form.role')}}</h5><hr>
    </div>

    <div class="block-form">
    	@if(empty($role['id']))
		{!!Form::open(['route'=>'admin.role.store','name' => 'frm','id'=>'user-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
		@else
		{!! Form::model($role, ['method' => 'PATCH', 'action' => ['Backend\RoleController@update', $role['id'] ],'id'=>'role-create-form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data']) !!}
		@endif
		
		@csrf
			<div class="form-row ">
				{{ Form::form_text('name', $role['name']??'',['id' => 'name', 'class' => 'form-control required' ]) }}
			</div>
			<div class="form-row mw-100">
				<div class="col-md-12 mt-2">
				<label for="category_id">{{ trans('form.role') }} </label>
				<select name="permiss[]" id="multi_select_row" class="form-control"  multiple="multiple">
					@foreach($permissions as $key => $val)
						<option value="{{ $key }}" {{ array_key_exists($key, $role['permissions'])?'selected':'' }} >{{ $val }}</option>
				  	@endforeach
				</select>
				{{ Form::hidden('old_permiss', json_encode($role['permissions'])) }}
				</div>
			</div>
			<div class="form-row">
				{{ Form::form_status('status',$role['status']??'',[]) }}				
			</div>
			<div class="form-row">
				<div class="mx-auto">
					@if(!empty($role['id']))
					<a onclick="return delete_confirm()" href="{{route('admin.role.destroy',[$role['id'], 'force'])}}" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt">{{trans('form.force_delete') }}</i></a>
					@endif
				</div>
			</div><hr>
			{{ Form::submit_back_button() }}
		{!! Form::close() !!}
	</div>
</div>