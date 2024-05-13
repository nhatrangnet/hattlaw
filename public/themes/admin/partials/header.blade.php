<header class="main-header-logo-left h-100" id="header" >
	<section class="container">
		<div class="row">
			<a class="navbar-brand logo ml-2" href="#">
				<img src="{{ url('storage/logo.png')}}" alt="logo" title="logo">
			</a>
			<div class="right-nav row">
				<nav class="navbar-nav header-menu admin_info">
						<a href="#" class="dropdown-toggle">
							<i class="fas fa-user-tie"></i>
							<span class="hidden-xs">{{ Session::get('admin')['name']??'admin guest' }}</span>
						</a>
						<ul class="dropdown-menu" style="display: none">
							<div class="card">
								<div class="card-body">
									<h5 class="card-title">{{ Session::get('admin')['name'] }}</h5>
									<h6 class="card-subtitle mb-2 text-muted">{{ trans('form.register')}} {{ date('d-m-Y',strtotime(Session::get('admin')['created_at'])) }}</h6>
									{{-- <p class="card-text">aaa</p> --}}
								</div>
								<div class="card-footer">
									<a href="{{ route('adminboard') }}" class="card-link btn btn-info btn-flat">{{ trans('menu.profile') }}</a>
									<a href="{{ route('admin.logout') }}" class="card-link btn btn-secondary btn-flat float-right">{{ trans('menu.logout')}}</a>
								</div>
							</div>
						</ul>
				</nav>

				<div class="lang_select">
				  <div id="choose_lang"><span class="lang_selected"></span></div>
				  <ul class="dropdown-menu text-center">
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
				  <input type="text" class="d-none" id="website_language_admin" value="{{Session::has('hatlaw_language')?Session::get('hatlaw_language'):'vi'}}">
				</div>
			</div>
		</div>
	</section>
</header>