<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i> {{ empty($tag->id)?trans('form.create'):trans('form.edit') }} Tag</h5><hr>
    </div>
    <div class="block-form">
    	@if(empty($tag->id))
		{!!Form::open(['route'=>'admin.tag.store','name' => 'frm','id'=>'tag-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
		@else
		{!! Form::model($tag, ['method' => 'PATCH', 'action' => ['Backend\TagController@update', $tag->id],'id'=>'tag-create-form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data']) !!}
		{{ Form::hidden('id', mahoa_giaima('mahoa',$tag->id)) }}
		@endif
		
		{{-- @csrf --}}
			<div class="form-row">
				{{ Form::form_text('name', $tag->name??'',['id' => 'name', 'class' => 'form-control required' ]) }}
			</div>

			<div class="form-row">
				{{ Form::form_status('status',$tag->status??'',['1' => trans('form.active'), '0' => trans('form.inactive')], ['id' => 'status', 'class' => 'form-control required' ]) }}
			</div>
			<div class="form-row">
				<div class="mx-auto">
					@if(!empty($tag->id))
					<a onclick="return delete_confirm()" href="{{route('admin.tag.destroy',[$tag->id, 'force'])}}" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt">{{trans('form.force_delete') }}</i></a>
					@endif
				</div>
			</div><hr>
			{{ Form::submit_back_button() }}
		{!! Form::close() !!}
	</div>
</div>