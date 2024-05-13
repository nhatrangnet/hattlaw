<section id="blog-category">
<div class="container">
  <div class="row">
@if(count($category['news']) == 0)
<div class="blog_post">
	<p class="line"></p>
	<div class="blog_title text-center col-12">
		<h4>{{ trans('frontend.updating') }}</h4>
	</div>
	<p class="line"></p>
</div>
@elseif(count($category['news']) == 1 )
	<div class="blog_post">
		<div class="blog_title text-center">
			<div class="post-thumb text-center">
				<?php
					$url1 = 'storage'.config('constant.image.blognews').'/'.$list_news[0]->image;
					if(!File::exists($url1)) $url1 ='images/basic/default-image.jpg';
					?>
		    	<img class="mw-100 border-0" alt="{{ $list_news[0]->title }}" title="{{ $list_news[0]->title }}"  src="{{ url($url1) }}">
		  	</div>
		  	<hr>
			<h1>{{ $list_news[0]->title }}</h1>
			<span class="small"></span>
			<h2>{!!$list_news[0]->summary !!}</h2>
		</div>
		<p class="line"></p>
		<content class="blog_content">
			{!!$list_news[0]->content !!}
		</content>
	</div>
@else
<div class="clearfix info text-center">
	<h2>{{ $category['name'] }}</h2>
	{{-- <h4>{!! $category['description'] !!}</h3> --}}
	{{-- <img class="mw-100 rounded h-25" src="https://sohopress.com/wp-content/uploads/2018/05/web-summit-feature_1200x675_hero_110717.jpg"> --}}
	<hr>
</div>
@foreach($list_news as $news)
<div class="mt-3 mb-2 w-100">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-3">
					<?php
					$url = 'storage'.config('constant.image.blognews').'/thumb/medium_'.$news->image;
					if(!File::exists($url)) $url ='images/basic/default-image.jpg';
					?>
					<img class="mw-100 rounded" src="{{ url($url) }}">
				</div>
				<div class="col-md-9">
					<div class="news-content">
						<a href="{{ route('blog.news', $news->slug??'') }}"><h2>{{ $news->title??'' }}</h2></a>
						<p>{!! $news->summary??'' !!}</p>
						
					</div>
					<div class="news-footer">
						<div class="news-author">
							<ul class="list-inline list-unstyled">
								<li class="list-inline-item text-secondary">
									<i class="fa fa-eye"></i>
									{{ $news->hit??'' }}
								</li>
								<li class="list-inline-item text-secondary">
									<i class="fa fa-calendar"></i>
									{{ date('d-m-Y', strtotime($news->updated_at??'')) }}
								</li>
								<li class="list-inline-item float-right">
									<a href="{{ route('blog.news', $news->slug??'') }}" class="btn btn-primary text-right">{{trans('frontend.readmore')}}</a>
								</li>
							</ul>
						</div>
					</div>
				</div> {{-- col-md-9 --}}
			</div>
		</div>
	</div>
</div>
@endforeach
<nav class="table-responsive d-flex justify-content-center" aria-label="pagination">
  {{ $list_news->links()}}
</nav>

@endif
</div>
</div>
</section>