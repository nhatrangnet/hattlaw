<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i> {{ config('constant.form.search') }}{{ trans('menu.store.name') }}</h5><hr>
    </div>
    <div class="block-form">
        {!!Form::open(array('url'=>'#','method' => 'post','name' => 'frm','id'=>'order-search-form','class' => 'form-horizontal form-bordered form-inline form-admin')) !!}
          <div class="form-row align-items-center justify-content-center w-100">
            <div class="form-group row">
                {{ Form::form_text_search('name','',['id' => 'name', 'class' => 'form-control' ]) }}
                {{ Form::form_text_search('phone','',['id' => 'phone', 'class' => 'form-control' ]) }}

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
        <h4 class="d-inline"><i class="fa fa-list"></i> {{ trans('form.list') }} {{ trans('menu.store.name') }}</h4>
        <a href="{{ route('store.report')}}" class="btn btn-warning float-right d-inline">{{ trans('menu.store.report')}}</a>
        <a href="{{ route('store.create')}}" class="btn btn-success float-right d-inline mr-3"  data-toggle="modal" data-target="#create_store">{{ trans('menu.store.create')}}</a>
        <a href="{{ route('store.statistic')}}" class="btn btn-info float-right d-inline mr-3">{{ trans('menu.store.statistic')}}</a>
      <hr>
    </div>
    <div class="block-form">
    <table class="table table-striped table-hover table-sm table-primary" id="product_table">
        <thead class="thead-blue">
            <tr>
                <th>Id</th>
                <th>{{ trans('form.name') }}</th>
                <th>{{ trans('form.price') }}</th>
                <th>{{ trans('form.quantity') }}</th>
                <th>{{ trans('form.active') }}</th>
                <th>{{ trans('form.updated_at') }}</th>
                <th class="text-center"></th>
            </tr>
        </thead>
    </table>
    </div>
</div>

<!-- Create popup Nhap hang -->
<div class="modal fade" id="create_store" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{trans('menu.store.create')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!!Form::open(['route'=>'store.store','name' => 'frm','id'=>'store-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
            {{ Form::label(trans("menu.product.name"), null, ['class' => 'control-label']) }}
            {{ Form::select('product', $product_list??'', 0, array_merge(['id' => 'product_list', 'class' => 'custom-select'] )) }}
            <br>
            <div>
            {{ Form::form_text('quantity', '',['id' => 'quantity', 'class' => 'form-control required' ]) }}
            {{ Form::label(trans("form.choose_brand"), null, ['class' => 'control-label']) }}
            {{ Form::form_select('brand_id', $brand_list, -1, ['class' => 'form-control1 required']) }}
            </div>
            <hr>
            {{ Form::submit_button() }}
        {!! form::close() !!}
      </div>
    </div>
  </div>
</div>