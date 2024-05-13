{{ Form::label(trans("form.$name"), null, ['class' => 'control-label']) }}
<div class="outerDivFull form-control border-0" >
	<div class="switchToggle">
	    <input type="checkbox" id="switch" name="{{$name}}" value="1" {{$value===0?'':'checked'}}>
	    <label for="switch">Toggle</label>
	</div>
</div>
{{-- {{ Form::select('size', ['L' => 'Large', 'S' => 'Small'], 'S',['id' => 'active', 'class' => 'form-control']) }} --}}

{{-- {{ Form::select($name, $options, $value, $attributes) }} --}}
