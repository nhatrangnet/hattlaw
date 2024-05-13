<h1 class="h2">{{trans('form.dashboard')}}</h1>
<hr>

<a href="{{ route('cart.create')}}" class="btn btn-info float-right d-inline">{{ trans('form.create')}}</a>

<div class="block">
    <div class="block-title">
        <h4 class=" d-inline"><i class="fa fa-list"></i> {{ trans('form.birthday') }} {{ trans('form.customer') }}</h4>
        <div class="d-inline float-right">
			<select class="custom-select" name="time_birthday_search" id="time_birthday_search">
				<option value="0" selected="selected">Trong tuần</option>
				<option value="1">Trong tháng</option>
			</select>
        </div>
    </div>
    <hr>
    </div>
    <div class="block-form">
    <table class="table table-striped table-hover table-sm table-primary" id="users_birthay_table">
        <thead class="thead-blue">
            <tr>
                <th>Id</th>
                <th>{{ trans('form.name') }}</th>
                <th>{{ trans('form.birthday') }}</th>
                <th>{{ trans('form.phone') }}</th>
                <th>{{ trans('form.address') }}</th>
                <th class="text-center">{{ trans('form.status') }}<i class="ml-2 fas fa-question-circle" title="{{trans('form.customer_active_this_month')}}"></i> </th>
            </tr>
        </thead>
    </table>
    </div>
</div>
