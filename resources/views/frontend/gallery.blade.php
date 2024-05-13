<br><h2 class="text-center">Gallery</h2>
<p class="line"></p>
{{-- <div id="gallery" class="mb-md-5 mt-md3">
  <div class="album" data-jgallery-album-title="Gia công kính 3D">
      <a href="https://imgur.com/4n4zXZf.jpg"><img src="https://imgur.com/4n4zXZf.jpg" alt="Photo 1" /></a>
      <a href="https://imgur.com/s5xAKzN.jpg"><img src="https://imgur.com/s5xAKzN.jpg" alt="Photo 2" /></a>
      <a href="https://imgur.com/SIJJPzc.jpg"><img src="https://imgur.com/SIJJPzc.jpg" alt="Photo 3" /></a>
  </div>
  <div class="album" data-jgallery-album-title="Gia công sắt">
      <a href="https://imgur.com/f5KXGsZ.jpg"><img src="https://imgur.com/f5KXGsZ.jpg" alt="Photo 4" /></a>
      <a href="https://imgur.com/d2uI1Xj.jpg"><img src="https://imgur.com/d2uI1Xj.jpg" alt="Photo 5" /></a>
      <a href="https://imgur.com/LfXUt07.jpg"><img src="https://imgur.com/LfXUt07.jpg" alt="Photo 6" /></a>
  </div>
</div> --}}
@if(!isset($category))
<div class="row">
  @foreach($category_list as $id => $category)
  @if(!empty($category['image_list']))
  <div class="col-md-3 col-sm-6 text-center mb-3">
    <div class="box7 mx-auto">
      <img src="{{ url('storage'.config('constant.image.gallery').'/'.$id.'/'.$category['image_list'][0]['image']) }}" alt="{{ $category['image_list'][0]['image'] }}">
      <div class="box-content">
        <a href="{{ route('gallery', $id) }}" class="fa fa-search white"><h3>{{ $category['name'] }}</h3></a>
      </div>
    </div>
  </div>
  @endif
  @endforeach
</div>

@else

{{-- @if(isset($category)) --}}
<h3 class="text-center text-uppercase">{{ $category_list[$category]['name'] }}</h3>
<div class="row">
  @foreach($category_list[$category]['image_list'] as $image)
  <?php
  $url = url('storage'.config('constant.image.gallery').'/'.$category.'/'.$image['image']);
  ?>
  <a class="elem" 
    href="{{ $url }}" 
    {{-- title="{{ $image->image }} {{ $image->updated_at }}"  --}}
    data-lcl-txt="{{ $image['description']??'' }}" 
    data-lcl-thumb="{{ $url }}">
    <span class="rounded" style="background-image: url({{ $url }});"></span>
  </a>
</ul>
@endforeach
</div>
@endif