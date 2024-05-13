<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i> {{ empty($review->id)?trans('form.create'):trans('form.edit') }} Review</h5><hr>
    </div>
    <div class="block-form">
    	@if(empty($review->id))
		{!!Form::open(['route'=>'admin.reviews.store','name' => 'frm','id'=>'review-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
		@else
		{!! Form::model($review, ['method' => 'PATCH', 'action' => ['Backend\ReviewController@update', $review->id],'id'=>'review-edit-form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data']) !!}
		{{-- {{ Form::hidden('id', mahoa_giaima('mahoa',$review->id)) }} --}}
		@endif
		
		{{-- @csrf --}}
			<div class="form-group row">
				{{ Form::label('Name', null, ['class' => 'col-form-label col-3 text-right']) }}
				{{ Form::text('author_name', $review->author_name??'',['class' => 'col-9 form-control required' ]) }}
			</div>
			<div class="form-group row">
				{{ Form::label('URL', null, ['class' => 'col-form-label col-3 text-right']) }}
				{{ Form::text('author_url', $review->author_url??'',['class' => 'col-9 form-control required' ]) }}
			</div>
			<div class="card col-12 col-md-5 offset-md-7">
				<div class="card-header">
					{{ Form::form_file('profile_photo',['class' => 'form-file' ]) }}
				</div>
				<div class="card-body text-center">
					<img src="{{ !empty($review->profile_photo)?\Storage::url(config('constant.image.review')."/".$review->profile_photo):\Storage::url(config('constant.no-image')) }}" class="img-fluid" alt="profile_photo-image">
				</div>
			</div>

			<div class="form-group row">
				{{ Form::label('Text', null, ['class' => 'col-form-label col-3 text-right']) }}
				{{ Form::textarea('text', $review->text??'',['class' => 'col-9 form-control required' ]) }}
			</div>
			<div class="form-group row">
				{{ Form::label('rating', null, ['class' => 'col-form-label col-3 text-right']) }}
				{{ Form::select('rating', $rating, $review->rating??'',['class' => 'col-9 form-control' ]) }}
			</div>

			<div class="form-group row">
				{{ Form::form_status('status',$review->status??'',['1' => trans('form.active'), '0' => trans('form.inactive')], ['id' => 'status', 'class' => 'col-9 form-control' ]) }}
			</div>
			<div class="form-group row">
				<div class="mx-auto">
					@if(!empty($review->id))
					<a onclick="return delete_confirm()" href="{{route('admin.reviews.destroy',[$review->id, 'force'])}}" class="btn btn-xs btn-danger delete_confirm"><i class="fa fa-trash-alt">{{trans('form.force_delete') }}</i></a>
					@endif
				</div>
			</div><hr>
			{{ Form::submit_back_button() }}
		{!! Form::close() !!}
	</div>
</div>