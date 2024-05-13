@if(!empty($images))
<div class="row">
@foreach($images as $image)
    {{-- <div class="col-sm-3 gallery_image text-center">
        <img class="img-thumbnail" src="{{ url('storage'.config('constant.image.gallery').'/'.$category.'/'.$image->image) }}">
        <p>{{ $image->image }} {{ $image->updated_at }}</p>
    </div> --}}
    <a class="elem" 
	   href="{{ url('storage'.config('constant.image.gallery').'/'.$category.'/'.$image->image) }}" 
	   {{-- title="{{ $image->image }} {{ $image->updated_at }}"  --}}
	   data-lcl-txt="{{ $image->description??'' }}" 
	   data-lcl-thumb="{{ url('storage'.config('constant.image.gallery').'/'.$category.'/'.$image->image) }}">
	  <span style="background-image: url({{ url('storage'.config('constant.image.gallery').'/'.$category.'/'.$image->image) }});"></span>
	</a>
</ul>
@endforeach
</div>
@endif