<div class="col-auto">
{{ Form::label($name, null, ['class' => 'sr-only']) }}
{{ Form::text( $name, $value, array_merge(['placeholder' => "Search $name"], $attributes)) }}
</div>