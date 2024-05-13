<?php
$lang = session('hatlaw_language')??'vi';
// echo "<pre>";print_r($nhanvien_list);die;
?>
<section id="our-team">
	<div class="container">
		<div class="row ">
			<h1 class="text-center category_title">Our team</h1><hr>
			<div class="image text-center col-12">
				<img src="https://i.imgur.com/50MpddF.jpg" alt="introduce-us" class="img-fluid img-thumbnail">
			</div>
			<div class="nhanvien_list col-12 d-flex">
				@if(count($nhanvien_list) > 0)
				@foreach($nhanvien_list as $nhanvien)
				@php
				$metadata = json_decode($nhanvien->metadata, true);

				if($lang == 'vi'){
					$des = $nhanvien->description;
					$name = $nhanvien->name;
					$position = $metadata['position']['vi'];
				}
				else{
					$des = $nhanvien->en_description;
					$name = $nhanvien->en_name;
					$position = $metadata['position']['en'];
				}
				$avatar = Storage::url(config('constant.image.admin').config('constant.image.avatar').'/'.$nhanvien->avatar);
				$image = Storage::url(config('constant.image.admin').config('constant.image.avatar').'/'.$nhanvien->image);
				@endphp

				<div class="nhanvien">
					<img src="{{$avatar}}" class="img-fluid avatar" alt="{{$nhanvien->slug}}">
					<div class="profile">
						<div class="name">
							<h5>{{$name??''}}</h5>
							<p>{{$position??''}}</p>
						</div>
						<div class="visit">
							<p data-toggle="modal" data-target="#{{ $nhanvien->slug??'' }}">
								View profile
							</p>
						</div>
					</div>

					<!-- Modal -->
					<div class="modal fade" id="{{ $nhanvien->slug }}" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">{{ $name??'' }}</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<div class="image">
										<img src="{{$image}}" alt="{{ $nhanvien->name??'' }}" class="img-fluid">
									</div>
								</div>
								<div class="modal-body text-justify">
									<h4>{{$position}}</h4>
									{!! $des??'' !!}
								</div>
							</div>
						</div>
					</div>

				</div>
				@endforeach
				@endif
			</div>
		</div>
	</div>
</section>
