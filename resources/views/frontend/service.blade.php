<section id="service-news">
<div class="container">
	<div class="row">
		<h2 class="text-center category_title">
			@php
				if(session('hatlaw_language') == 'vi'){
					echo $service->name;
				}
				else{
					echo $service->en_name??$service->name;
				}
			@endphp
		</h2><hr>
			@if(!empty($service->image))
			<div class="image text-center {{ ($frame=="col")?'col-md-6 d-none d-md-block':'col-12 p-1' }}">
				<img src="{{\Storage::url(config('constant.image.service').'/'.$service->image)}}" alt="{{$service->slug}}" class="img-fluid">
			</div>
			@endif

			<div class="content col-12 {{ ($frame=="col")?'col-md-6':'' }} p-1 ">
			@php
				if(session('hatlaw_language') == 'vi'){
					echo $service->description;
				}
				else{
					echo $service->en_description??$service->description;
				}
			@endphp
			</div>
	</div>
</div>
</section>