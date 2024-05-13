{{ Form::label($name, null, ['class' => 'control-label']) }}
{{ Form::password($name, array_merge(['placeholder' => 'password'], $attributes)) }}