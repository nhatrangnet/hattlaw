{!! Form::model($user, ['method' => 'POST', 'action' => ['Frontend\UserController@update_user'],'id'=>'user_edit_form', 'class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
{{Form::hidden('admin_id', $user['id']??'')}}

{{ Form::form_text('name', $user['name']??'',['id' => 'name', 'class' => 'form-control required' ]) }}

{{ Form::form_text('phone', $user['phone']??'',['id' => 'phone', 'class' => 'form-control' ]) }}
{{ Form::form_text('email', $user['email']??'',['id' => 'email', 'class' => 'form-control' ]) }}
{{ Form::form_text('address', $user['address']??'',['id' => 'address', 'class' => 'form-control' ]) }}
{{ Form::label(trans("form.birthday"), null, ['class' => 'control-label']) }}
<div class="input-group date" id="birthday" data-target-input="nearest">
    {{ Form::form_time_search('birthday', date('d-m-Y', strtotime($user['birthday'])),['placeholder' => "dd-mm-yyyy",'class' => 'form-control datetimepicker-input' ]) }}
    <div class="input-group-append" data-target="#birthday" data-toggle="datetimepicker">
        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
    </div>
</div>
<hr>
{{ Form::submit_button() }}
{!! Form::close() !!}
