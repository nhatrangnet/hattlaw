<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i> {{ trans('form.search') }} {{ trans('form.role') }}</h5><hr>
    </div>
    <div class="block-form">
        {!!Form::open(array('url'=>'#','method' => 'post','name' => 'frm','id'=>'role_search_form','class' => 'form-horizontal form-bordered form-inline form-admin')) !!}
          <div class="form-row align-items-center justify-content-center w-100">
            <div class="form-group row">
                {{ Form::form_text_search('name','',['id' => 'name', 'class' => 'form-control' ]) }}
                {{ Form::form_text_search('email','',['id' => 'email', 'class' => 'form-control' ]) }}

                <div class="col-auto">
                    <div class="input-group date" id="datetimepicker_from" data-target-input="nearest">
                        {{ Form::form_time_search('datetimepicker_from','',['placeholder' => "Search from date",'class' => 'form-control datetimepicker-input' ]) }}
                        <div class="input-group-append" data-target="#datetimepicker_from" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="input-group date" id="datetimepicker_to" data-target-input="nearest">
                        {{ Form::form_time_search('datetimepicker_to','',['placeholder' => "Search to date",'class' => 'form-control datetimepicker-input' ]) }}
                        <div class="input-group-append" data-target="#datetimepicker_to" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>

            </div>
            {{ Form::submit_button() }}
            
          </div>
        {!! Form::close() !!}
    </div>
</div><hr>

<div class="block">
    <div class="block-title">
      <h4 class="d-inline"><i class="fa fa-list"></i> {{ trans('form.list') }} </h4>
      {{-- @if(array_key_exists(config('constant.permissions.content.role_create'),$permiss)  !== false || $super_admin) --}}
        {!!Form::open(array('url'=>'#','method' => 'post','name' => 'frm','id'=>'ajax_image_by_gallery_category','class' => 'form-inline float-right','enctype' => 'multipart/form-data')) !!}
            {{ Form::select('category', $category_list, '', ['class' => 'custom-select form-control','id' => 'select_gallery_category'] ) }}
            <button type="submit" class="d-inline btn btn-primary mr-2 ml-2">View</button>
            <a href="{{ route('admin.galleryimage.create')}}" class="btn btn-info ml-2 d-inline">{{ trans('form.create')}}</a>
        {!! Form::close() !!}
        
      {{-- @endif --}}
      <hr>
    </div>
    <div class="block-form">
        
        <div id="ajax_galley_image">
            
        </div>
    </div>
</div>