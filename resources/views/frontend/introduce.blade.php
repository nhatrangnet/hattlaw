<section id="introduce">
	<div class="container">
		<div class="row">
			<h1 class="text-center category_title">{{ Theme::bind('config')['company_name'] }}</h1><hr>
			<div class="content" data-aos="{{ config('constant.aos')[rand(0,21)]}}" data-aos-duration="1000">
				<div class="image text-center">
					<img src="https://i.imgur.com/qMvDIhq.jpg" alt="introduce-us" class="img-fluid img-thumbnail">
				</div>
			@php
				if(session('hatlaw_language') == 'vi'){
					echo $introduce['vi'];
				}
				else{
					echo $introduce['en']??$introduce['vi'];
				}
			@endphp

			<div class="our-team">
				team
			</div>
			</div>
		</div>
	</div>
</section>