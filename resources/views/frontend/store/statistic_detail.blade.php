<table class="table table-bordered table-striped table-hover table-sm table-primary">
    <thead class="thead-info">
        <tr>
            <th class="text-right"></th>
            @if(!empty($product_list))
            @foreach($product_list as $product)
                <th class="text-center" colspan="2">{{$product['name']}}: {{$product['quantity']}}</th>
            @endforeach
            @endif
            
        </tr>
    </thead>
        <tr>
            <th class="text-center">{{trans('form.date')}}</th>
            @foreach($product_list as $product)
                <th class="text-center">{{trans('form.import')}}</th>
                <th class="text-center">{{trans('form.export')}}</th>
            @endforeach
            
        </tr>

        @foreach($statistic as $date => $product)
        <tr>
            <th class="text-center">{{date("d-m-Y", strtotime($date))}}</th>
            @foreach($product as $product_id => $val)
                <th class="text-center">{{($val['import'])}}</th>
                <th class="text-center">{{$val['export']}}</th>
            @endforeach
        </tr>
        @endforeach
</table>