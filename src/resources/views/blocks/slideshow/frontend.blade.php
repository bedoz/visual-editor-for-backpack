<div class="row">
    <div class="col-12 slideshow">
        @if (isset($value) && !empty($value))
            @php
                $valueDecoded = json_decode($value);
            @endphp
        @endif
        @if (isset($valueDecoded) && is_array($valueDecoded) && count($valueDecoded))
            @foreach($valueDecoded as $key => $file_path)
                <img src="{{asset(\Storage::disk('public')->url($file_path->image))}}" alt="" class="img-fluid">
            @endforeach
        @endif
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.slideshow').slick({});
    });
</script>