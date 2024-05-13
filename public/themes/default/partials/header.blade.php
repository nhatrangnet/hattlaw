@php
$blog_categories = Theme::bind('blog_categories');
$services = Theme::bind('services');
$lang = session('hatlaw_language')??'vi';
@endphp

{{-- menu logo left --}}
<header class="main-header-logo-left container-fluid">
	<section id="header" class="container">
		<div class="row">
			<div class="logo" data-aos="flip-left" data-aos-duration="1500" data-aos="{{ config('constant.aos')[rand(0,21)]}}">
				<a class="navbar-brand logo ml-md-3" href="{{ url('/') }}">
					<img src="{{ url('storage/logo.png') }}" alt="logo" title="logo">
				</a>
			</div>
			
			<nav class="sub-menu d-none d-md-flex align-items-center" data-aos="{{ config('constant.aos')[rand(0,21)]}}">
				<div class="mr-md-3 d-none d-sm-block"><a href="mail:admin@hatt.vn"><i class="far fa-envelope mr-1"></i>admin@hatt.vn</a></div>
				<div class="mr-md-3"><a href="tel:01215683869"><i class="fas fa-phone-volume mr-1"></i>(+84) 86201 7723</a></div>
				<div class="d-none d-md-block"><a href="#"><i class="far fa-clock mr-1"></i>T2 - T7 từ 8:30 đến 18:30</a></div>
				<div class="col-auto social-icon">
					<a href="https://www.facebook.com/profile.php?id=100046527083151"><i class="fab fa-facebook-square"></i></a>
					{{-- <a href="https://plus.google.com/+Nhatrangdevweb"><i class="fab fa-facebook-messenger"></i></a> --}}
					{{-- <a href="https://www.youtube.com/channel/UC6Z4VBqVgIpxS8cQGt83INQ"><i class="fab fa-youtube-square"></i></a> --}}
				</div>
			</nav>
			<div class="dropdown lang_select">
			  <div id="choose_lang"><span class="lang_selected"></span></div>
			  <ul class="lang_list text-center">
			    <li>
			    	<a href="{{ route('change_language','vi') }}"><img class="lang_vi" src="{{ url('storage/basic/vi.png') }}" alt="vietnam-language" />
			    	</a>
			    </li>
			    <li>
			    	<a href="{{ route('change_language','en') }}">
			        <img class="lang_en" src="{{ url('storage/basic/en.png') }}" alt="english-language" />
			      </a>
		      </li>
			  </ul>
			  <input type="text" class="d-none" id="website_language" value="{{Session::has('hatlaw_language')?Session::get('hatlaw_language'):'vi'}}">
			</div>

			<div class="toggler-icon">
				<a href="#">
					<i class="fas fa-bars" aria-hidden="true"></i>
				</a>
			</div>
			<div class="mobile-menu">
				<span class="close"><i class="fas fa-times fa-2x" aria-hidden="true"></i></span>
				<a class="home d-none d-md-block" href="{{ route('index') }}"><i class="fas fa-home"></i></a>
				<nav class="w-100">
					<ul>
						<li class="nav-item">
							<a class="nav-link" data-aos="fade-up-left" href="{{ route('introduce') }}">{{ trans('frontend.introduce') }}</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="{{ route('our.team') }}">Our team</a>
						</li>

						@if(!empty($services))
						@foreach ($services as $key => $value)
						<li class="nav-item">
							@if(!empty($value['sub']))
							<span class="nav-link parent" data-aos="fade-up-left" href="{{ route('service', $key) }}">{{ $value[$lang]['name'] }}<i class="fas fa-angle-down"></i></span>
							<ul class="sub-menu">
								@foreach($value['sub'] as $key_sub => $sub)
								<li><a href="{{ route('service', $sub['slug']) }}" class="documents"><i class="fas fa-angle-right mr-1"></i>{{ $sub[$lang]['name']??$sub['vi']['name'] }}</a></li>
								@endforeach
							</ul>
							@else
							<a class="nav-link" href="{{ route('service', $value['slug']) }}">{{ $value[$lang]['name']??$value['vi']['name'] }}</a>
							@endif
						</li>
						@endforeach
						@endif

						@if(!empty($blog_categories))
							@if(count($blog_categories) == 1)
								@foreach ($blog_categories as $cat)
								<li class="nav-item">
									@if($lang == 'en')
										<a class="nav-link" href="{{ route('blog.category',$cat->slug) }}">{{$cat->en_name??$cat->name}}</a>
									@else
										<a class="nav-link" href="{{ route('blog.category',$cat->slug) }}">{{$cat->name}}</a>
									@endif
								</li>
								@endforeach
							@else
							
							<li class="nav-item">
								<span class="nav-link parent" data-aos="fade-up-left" href="{{ route('service', $key) }}">{{ $value[$lang]['name'] }}<i class="fas fa-angle-down"></i></span>
								<ul class="sub-menu">
									@foreach ($blog_categories as $sub)
										@if($lang == 'en')
											<li><a href="{{ route('blog.category', $sub->slug) }}" class="documents"><i class="fas fa-angle-right mr-1"></i>{{ $sub->en_name??$sub->name }}</a></li>
										@else
											<li><a href="{{ route('blog.category', $sub->slug) }}" class="documents"><i class="fas fa-angle-right mr-1"></i>{{ $sub->name }}</a></li>
										@endif
									@endforeach
								</ul>
							</li>
							
							@endif
						@endif


						<li class="nav-item">
							<a class="nav-link" href="{{ route('contact') }}">{{ trans('frontend.contact') }}</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</section>
</header>
<!--   menu logo left end -->