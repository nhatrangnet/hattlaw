{{-- <div class="col-auto">
	<div class="input-group date" id="$name" data-target-input="nearest">
		{{ Form::text( $name, $value, array_merge(['name' => "$name",'placeholder' => "Search $value", 'data-target' => "#$name"], $attributes)) }}
		<div class="input-group-append" data-target="#$name" data-toggle="datetimepicker">
			<div class="input-group-text"><i class="fa fa-calendar"></i></div>
		</div>
	</div>
</div> --}}

{{ Form::text( $name, $value, array_merge(['name' => "$name", 'data-target' => "#$name"], $attributes)) }}