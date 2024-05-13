{{ Form::label($name, null, ['class' => 'control-label']) }}
{{ Form::file($name, array_merge(['placeholder' => 'file'], $attributes)) }}