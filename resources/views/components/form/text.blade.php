@if($name != 'email')
{{ Form::label(trans("form.$name"), null, ['class' => 'control-label']) }}
{{ Form::text($name, $value, array_merge(['class' => 'form-control', 'placeholder' => trans("form.$name")], $attributes)) }}

@else

{{ Form::label(trans("$name"), null, ['class' => 'control-label']) }}
{{ Form::text($name, $value, array_merge(['class' => 'form-control', 'placeholder' => trans("$name")], $attributes)) }}

@endif
