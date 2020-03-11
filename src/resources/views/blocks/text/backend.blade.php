<div>
    <label>{{$class::$label}}</label>
</div>
<textarea
    class="form-control"
    name="text[]"
></textarea>
{{-- HINT --}}
@if (isset($class::$hint))
    <p class="help-block">{!! $class::$hint !!}</p>
@endif