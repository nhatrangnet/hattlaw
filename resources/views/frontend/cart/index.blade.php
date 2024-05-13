<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i> {{ config('constant.form.search') }}{{ trans('menu.order.name') }}</h5><hr>
    </div>
    <div class="block-form">
        {!!Form::open(array('url'=>'#','method' => 'post','name' => 'frm','id'=>'order-search-form','class' => 'form-horizontal form-bordered form-inline form-admin')) !!}
          <div class="form-row align-items-center justify-content-center w-100">
            <div class="form-group row">
                {{ Form::form_text_search('name','',['id' => 'name', 'class' => 'form-control' ]) }}
                {{ Form::form_text_search('phone','',['id' => 'phone', 'class' => 'form-control' ]) }}
                {{ Form::select('nhanvien', $list_nhanvien,'',['class' => 'form-control mr-3']) }}

                {{ Form::radio('time_type', 0,  true,['class' => 'report_time_type'] ) }}
                {{ Form::select('report_time', timeStatisticSearch(),'',['class' => 'report_time form-control']) }}
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

            </div>
            {{ Form::submit_button() }}
            
          </div>
        {!! Form::close() !!}
    </div>
</div><hr>

<div class="block">
    <div class="block-title">
        <h4 class="d-inline"><i class="fa fa-list"></i> {{ trans('form.list') }} {{ trans('menu.order.name') }}</h4>
        <a href="{{ route('cart.create')}}" class="btn btn-info float-right d-inline">{{ trans('form.create')}}</a>
      <hr>
    </div>
    <div class="block-form position-relative">
    <table class="table table-striped table-hover table-sm table-primary" id="order_table">
        <thead class="thead-blue">
            <tr>
                <th>Id</th>
                <th>{{ trans('form.name') }}</th>
                <th>{{ trans('form.phone') }}</th>
                <th>{{ trans('form.address') }}</th>
                <th>{{trans("form.nhanvien")}}</th>
                <th class="sum_total" style="color: #ffeb00">{{ trans('form.total') }}</th>
                <th>{{ trans('form.status') }}</th>
                <th>{{ trans('form.updated_at') }}</th>
                <th class="text-center"></th>
            </tr>
        </thead>
    </table>
    <div class="position-absolute" style="right: 550px;bottom:0">{{trans('form.total')}} <b style="color:rgb(154, 142, 0)"><span class="sum_total"></span></b><span>{{trans('form.money_symbol')}}</span></div>
    </div>
</div>