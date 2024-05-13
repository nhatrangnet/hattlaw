{{-- @if($name != 'parent_id')
{{ Form::label(trans("form.$name"), null, ['class' => 'control-label']) }}
@endif --}}
{{ Form::select($name, $list, $value, array_merge(['class' => 'form-control custom-select'] )) }}
{{-- {{ Form::select($name, $list, $value, array_merge(['class' => 'custom-select','placeholder' => trans('form.category')])) }} --}}