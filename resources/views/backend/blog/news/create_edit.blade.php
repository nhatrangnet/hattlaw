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
				<h5><i class="fa fa-edit"></i> {{ empty($news['id'])?trans('form.create'):trans('form.edit') }} {{ trans('menu.news.name') }}</h5><hr>
			</div>
			<div class="block-form">
				@if(empty($news['id']))
				{!!Form::open(['route'=>'admin.blog_news.store','name' => 'frm','id'=>'news-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
				@else
				{!! Form::model($news, ['method' => 'PATCH', 'action' => ['Backend\Blog\NewsController@update', $news['id']],'id'=>'news-create-form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data']) !!}
				{{ Form::hidden('id', mahoa_giaima('mahoa',$news['id'])) }}
				{{ Form::hidden('old_image', $news['old_image']) }}
				{{ Form::hidden('old_image_slide', $news['old_image_slide']) }}
				@endif

				<div class="form-row">
					<div class="col-md-6">
						{{ Form::form_text('title', $news['title']??'',['id' => 'title', 'class' => 'form-control required' ]) }}
						{{ Form::form_status('status',$news['status']??'',['1' => trans('form.active'), '0' => trans('form.inactive')], ['id' => 'status', 'class' => 'form-control required' ]) }}


						

						<label for="description">{{ trans('form.summary') }}</label>
						{{ Form::textarea('summary',$news['summary']??'',['id' => 'summary', 'class' => 'summernote form-control' ]) }}
					</div>
					<div class="col-md-6">
						<label for="category_id" class="popup">{{ trans('form.category') }} <span class="font-italic small">{{ trans('form.press_ctrl_select_multi') }}</span></label>
						{{ Form::form_select_multiple('category_id[]', $category, $news['old_category']??'', ['id' => 'category_id']) }}

						{{ Form::form_text('metades', $news['metades']??'',['id' => 'metades', 'class' => 'form-control' ]) }}
						{{ Form::form_text('metakey', $news['metakey']??'',['id' => 'metakey', 'class' => 'form-control' ]) }}

						<label class="popup">Meta robot</span></label>
						{{ Form::form_select('metarobot', metarobot(), $news['metarobot']??'index, follow', ['id' => 'metarobot']) }}
					</div>
				</div>
				<div class="form-group">
					<label for="content">{{ trans('form.content') }}</label>
					{{ Form::textarea('content',$news['content']??'',['id' => 'content', 'class' => 'summernote_large form-control' ]) }}

				</div>
				
			</div>
		</div>
	</div>

	<div class="tab-pane fade show" id="english" role="tabpanel" aria-labelledby="english-tab">
		<div class="block">
			<div class="block-title">
				<h5><i class="fa fa-edit"></i> {{ empty($news['id'])?'Create':'Edit' }} News</h5><hr>
			</div>
			<div class="block-form">
			
				<div class="form-row">
					<div class="col-md-6">
						{{ Form::label('Name', null, ['class' => 'control-label']) }}
						{{ Form::text('en_title', $news['en_title']??'',['class' => 'form-control required' ]) }}

						<label for="description">Summary</label>
						{{ Form::textarea('en_summary',$news['en_summary']??'',['class' => 'summernote form-control' ]) }}
					</div>
					<div class="col-md-6">
						{{ Form::label('Meta description', null, ['class' => 'control-label']) }}
						{{ Form::text('en_metades', $news['en_metades']??'',['class' => 'form-control' ]) }}

						{{ Form::label('Metakey', null, ['class' => 'control-label']) }}
						{{ Form::text('en_metakey', $news['en_metakey']??'',['class' => 'form-control' ]) }}

						<label class="popup">Meta robot</span></label>
						{{ Form::form_select('en_metarobot', metarobot(), $news['en_metarobot']??'index, follow', ['class' => 'form-control']) }}
					</div>
				</div>
				<div class="form-group">
					<label for="content">Content</label>
					{{ Form::textarea('en_content',$news['en_content']??'',['class' => 'summernote_large form-control' ]) }}
				</div>
			</div>
		</div>
	</div>

	<div class="tab-pane fade show" id="image" role="tabpanel" aria-labelledby="image-tab">
		<div class="card">
			<div class="card-header">
				{{ Form::form_file('image',['id' => 'image', 'class' => 'form-file' ]) }}
			</div>
			<div class="card-body text-center">
				<img src="{{ $news['old_image'] }}" class="img-fluid" alt="image">
			</div>
		</div>
		<br>
		<div class="card">
			<div class="card-header">
				{{ Form::form_file('image_slide',['id' => 'image_slide', 'class' => 'form-file' ]) }}
			</div>
			<div class="card-body text-center">
				<img src="{{ $news['old_image_slide'] }}" class="img-fluid" alt="slide-image">
			</div>
		</div>
	</div>

	{{ Form::submit_back_button() }}
	{!! Form::close() !!}
</div>