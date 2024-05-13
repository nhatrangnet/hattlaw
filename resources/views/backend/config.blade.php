<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i>{{ trans('form.config') }} </h5><hr>
    </div>
    <div class="block-form">
    	{!!Form::open(['route'=>'admin.config.save','name' => 'frm','id'=>'config-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
			<div class="form-row">
				{{ Form::form_text('company_name', $config['company_name']??'',['id' => 'company_name', 'class' => 'form-control required' ]) }}
			</div>

			<div class="form-row">
				{{ Form::form_text('company_add', $config['company_add']??'',['id' => 'company_add', 'class' => 'form-control required' ]) }}
			</div>
			<div class="form-row">
				{{ Form::form_text('company_phone', $config['company_phone']??'',['id' => 'company_phone', 'class' => 'form-control' ]) }}
			</div>
			<div class="form-row">
				{{ Form::form_text('company_email', $config['company_email']??'',['id' => 'company_email', 'class' => 'form-control' ]) }}
			</div>
			<div class="form-row">
				{{ Form::form_text('company_mst', $config['company_mst']??'',['id' => 'company_mst', 'class' => 'form-control' ]) }}
			</div>

			<div class="form-row">
				{{ Form::form_text('website_name', $config['website_name']??'',['id' => 'website_name', 'class' => 'form-control' ]) }}
			</div>

			<div class="form-row mt-2">
				{{ Form::form_text('metades', $config['metades']??'',['id' => 'metades', 'class' => 'form-control' ]) }}
			</div>
			<div class="form-row mt-2">
				{{ Form::form_text('metakey', $config['metakey']??'',['id' => 'metakey', 'class' => 'form-control' ]) }}
			</div>
			<div class="form-row mt-2">
				{{ Form::form_select('metarobot', metarobot(), $config['metarobot']??'index, follow', ['id' => 'metarobot']) }}
			</div>
			<div class="form-row mt-2">
				<div class="card col-md-6">
					<div class="card-header">
						{{ Form::form_file('logo',['id' => 'logo', 'class' => 'form-control' ]) }}
					</div>
					<div class="card-body text-center">
				   		<img src="{{ url('storage/logo.png')}}" alt="logo" class="img-fluid mw-75">
				  	</div>
				</div>
				<div class="card col-md-6">
					<div class="card-header">
						{{ Form::form_file('watermark',['id' => 'watermark', 'class' => 'form-control' ]) }}
					</div>
					<div class="card-body text-center">
				   		<img src="{{ url('storage/watermark.png')}}" alt="watermark" class="img-fluid">
				  	</div>
				</div>
			</div>
			<div class="col-md-6 mb-2">
				<div class="card">
					{{ Form::hidden('old_default', $config['defaultslide']??null) }}
					<div class="card-header multi_image_block" data-name="defaultslide">
						{{ Form::label('defaultslide', null, ['class' => 'control-label']) }}
						<button class="btn btn-info add_image_btn float-right">{{ trans('form.add_more') }}</button>
						<hr>
					</div>
					<div class="card-body text-center multi_image_old">
						{!! $config['old_cover']??null !!}
					</div>

				</div>
			</div>

			{!! Form::submit_button() !!}
    	{!! Form::close() !!}
    </div>
</div>