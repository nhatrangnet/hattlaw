<div class="block">
    <div class="block-title">
      <h5><i class="fa fa-search"></i>{{ trans('form.list') }} {{ trans('menu.category.name') }}</h5><hr>
    </div>
    <div class="block-form">
        {!!Form::open(array('url'=>'#','method' => 'post','name' => 'frm','id'=>'blog-category-search-form','class' => 'form-horizontal form-bordered form-inline form-admin')) !!}
          <div class="form-row align-items-center justify-content-center w-100">
            <div class="form-group row">
                {{ Form::form_text_search('name','',['id' => 'name', 'class' => 'form-control' ]) }}

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
        <h4 class=" d-inline"><i class="fa fa-list"></i> {{ trans('form.list') }} {{ trans('menu.category.name') }}</h4>
        <a href="{{ route('admin.product-category.create')}}" class="btn btn-info float-right d-inline">{{ trans('form.create')}}</a>
      <hr>
    </div>
    <div class="block-form">
    <table class="table table-striped table-hover table-sm table-primary" id="product_category_table">
        <thead class="thead-blue">
            <tr>
                <th>Id</th>
                <th>{{ trans('form.name') }}</th>
                <th>{{ trans('form.status') }}</th>
                <th>{{ trans('form.updated_at') }}</th>
                <th class="text-center"></th>
            </tr>
        </thead>
    </table>
    </div>
</div>