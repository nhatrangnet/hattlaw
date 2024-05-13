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
      {{-- <a href="{{ route('admin.gallery_category.create')}}" class="btn btn-info float-right d-inline popup-btn">{{ trans('form.create')}}</a> --}}
        
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-info d-inline float-right" data-toggle="modal" data-target="#popup1">{{ trans('form.create')}}</button>
      {{-- @endif --}}
      <hr>
  </div>
  <div class="block-form">
    <table class="table table-striped table-hover table-sm table-primary" id="admin_gallery_category_table">
        <thead class="thead-blue">
            <tr>
                <th>Id</th>
                <th>{{ trans('form.name') }}</th>
                <th>{{ trans('form.status') }}</th>
                <th>{{ trans('form.updated_at') }}</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
</div>



<!-- Modal popup -->
<div class="modal fade" id="popup1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">{{ trans('form.gallery.category') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!!Form::open(['route'=>'admin.gallery_category.store','name' => 'frm','id'=>'user-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
            {{ Form::form_text('name', $role['name']??'',['id' => 'name', 'class' => 'form-control required' ]) }}
            <div class="form-row">
                {{ Form::form_status('status',$role['status']??'',[]) }}                
            </div>
            {{ Form::submit_button() }}
        {!! form::close() !!}
      </div>
    </div>
  </div>
</div>