<div class="card right_sidebar">
	<img class="card-img-top" src="https://imgur.com/cBxnWMG.jpg" alt="call center" title="call center">
	<div class="card-body">
		{{-- <h4 class="mb-1 color">{{trans('frontend.send_email') }}</h4> --}}
		<h5 class="card-text">{{ trans('frontend.contact_intro') }} </h5><hr>
		<h6 class="card-title mb-2"><i class="fas fa-phone-square red mr-1"></i><span>{{ Theme::bind('config')['company_phone'] }}</span></h6>
		<h6><i class="fas fa-map-marked-alt red mr-1"></i><span>{{ Theme::bind('config')['company_add'] }}</span></h6>
		<h6><i class="fas fa-envelope red mr-1"></i><a class="btn-link" href="mailto:{{ Theme::bind('config')['company_email'] }}">{{Theme::bind('config')['company_email']}}</a></h6>
	</div>
</div>