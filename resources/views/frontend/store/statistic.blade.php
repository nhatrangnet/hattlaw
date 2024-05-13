<div class="block-form">
    {!!Form::open(array('url'=>'#','method' => 'post','name' => 'frm','id'=>'statistic_search_form','class' => 'form-horizontal form-bordered form-inline form-admin')) !!}
    <div class="form-row align-items-center justify-content-center w-100">
        {{ Form::radio('time_type', 0,  true,['class' => 'report_time_type'] ) }}
        {{ Form::select('report_time', timeStatisticSearch(),0,['class' => 'report_time form-control']) }}
        {{ Form::radio('time_type', 1,  false, ['class' => 'datetimepicker_type ml-4'] ) }}
        <div class="col-auto" style="padding-left: 0;">
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
        {{ Form::submit_button() }}
    </div>
    {!! Form::close() !!}
</div>
<div class="block-form" id="statistic_detail">
</div>