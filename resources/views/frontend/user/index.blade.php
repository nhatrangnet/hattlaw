<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i> {{ config('constant.form.search') }} {{trans('menu.user.name')}}</h5><hr>
  </div>
  <div class="block-form">
    {!!Form::open(array('url'=>'#','method' => 'post','name' => 'frm','id'=>'user-search-form','class' => 'form-horizontal form-bordered form-inline form-admin')) !!}
    <div class="form-row align-items-center justify-content-center w-100">
        <div class="form-group row">
            {{ Form::form_text_search('name','',['id' => 'name', 'class' => 'form-control' ]) }}
            {{ Form::form_text_search('phone','',['id' => 'phone', 'class' => 'form-control' ]) }}


                {{-- <div class="col-auto">
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
                </div> --}}

            </div>
            {{ Form::submit_button() }}

        </div>
        {!! Form::close() !!}
    </div>
</div><hr>

<div class="block">
    <div class="block-title">
        <h4 class="d-inline"><i class="fa fa-list"></i> {{ config('constant.form.list') }} User</h4>
        
        <!-- Button trigger modal -->
        <a href="{{ route('admin.user.create')}}" class="btn btn-info float-right d-inline" data-toggle="modal" data-target="#create_user">{{ trans('form.create')}}</a>
        
        <div class="d-inline float-right mr-5">
            <select class="custom-select" name="time_birthday_search" id="time_birthday_search">
                @foreach($time_birthday_search as $val => $time)
                <option value="{{$val}}">{{$time}}</option>
                @endforeach
            </select>
        </div>
        <span class="d-inline float-right mt-1 mr-1">{{ trans('form.birthday') }} {{ trans('form.customer') }}</span>
    </div>
    <hr>
    <div class="block-form">
        <table class="table table-striped table-hover table-sm table-primary" id="users-table">
            <thead class="thead-blue">
                <tr>
                    <th>Id</th>
                    <th>{{trans('form.name')}}</th>
                    <th>{{trans('form.address')}}</th>
                    <th>Phone</th>
                    <th>{{trans('form.birthday')}}</th>
                    {{-- <th>{{trans('form.status')}}</th> --}}
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Create user Modal popup -->
<div class="modal fade" id="create_user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ trans('form.create') }} {{trans('menu.user.name')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
  </div>
  <div class="modal-body">
    {!!Form::open(['route'=>'user.store','name' => 'frm','id'=>'user-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
    <div>
        {{ Form::form_text('name', '',['id' => 'name', 'class' => 'form-control required' ]) }}
    </div>
    {{ Form::form_text('phone', '',['id' => 'phone', 'class' => 'form-control' ]) }}
    {{ Form::form_text('email', '',['id' => 'email', 'class' => 'form-control' ]) }}
    {{ Form::form_text('address', '',['id' => 'address', 'class' => 'form-control' ]) }}
    {{ Form::label(trans("form.birthday"), null, ['class' => 'control-label']) }}
    <div class="input-group date" id="birthday" data-target-input="nearest">
        {{ Form::form_time_search('birthday', $user['birthday']??'',['placeholder' => "dd-mm-yyyy",'class' => 'form-control datetimepicker-input' ]) }}
        <div class="input-group-append" data-target="#birthday" data-toggle="datetimepicker">
            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
        </div>
    </div>
    <hr>
    {{ Form::submit_button() }}
    {!! form::close() !!}
</div>
</div>
</div>
</div>

{{-- Edit user --}}
<div class="modal fade" id="edit_user_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ trans('form.edit') }} {{trans('menu.user.name')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
        </div>
      <div class="modal-body" id="edit_user_html">
      </div>
  </div>
</div>
</div>