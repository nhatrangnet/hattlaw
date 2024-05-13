<section id="news">
<div class="container">
  <div class="row">
    <aside class="post-header blog-post-info col-12 text-center">
      <!-- Preview Image -->
      <div class="post-thumb text-center">
        <?php
          $url = 'storage'.config('constant.image.blognews').'/thumb/medium_'.$news->image;
        ?>
        @if(File::exists($url))
        <img class="img-fluid border-0" alt="{{ $news->title }}" title="{{ $news->title }}"  src="{{ url('storage'.config('constant.image.blognews').'/'.$news->image) }}">
        @endif
      </div>
      <hr>
      <!-- post title -->
      <h1 class="post-title">{{ $news->title }}</h1>
      <!-- post meta -->
      <ul class="list-inline list-unstyled">
        {{-- <li class="list-inline-item post-author"><a href="#" title="Author Chung Nguyễn">Chung Nguyễn</a></li> --}}
        <li class="list-inline-item"><time class="post-date published" datetime="{{ $news->created_at }}"><i class="fa fa-calendar"></i>{{ date('d-m-Y', strtotime($news->updated_at)) }}</time></li>
        <li class="list-inline-item post-views"><i class="far fa-eye"></i>{{ $news->hit }}</li>
        {{-- <li class="list-inline-item post-reply"><a href="{{ route('blog.news', $news->slug) }}#comments">11 Bình luận</a></li> --}}

        <!-- post tags -->
        <li class="list-inline-item text-dark"><i class="fas fa-tags"></i> </li>
        <li class="list-inline-item">
          {{-- <a href="https://chungnguyen.xyz/tag/laravel-5-8"><span class="label-link badge badge-dark" rel="tag"> </span></a> --}}
        </li>
        <!-- /post tags -->
      </ul>
      <hr>
    </aside>
    <section class="post-body post-content">
      <div class="content">
        {!! $news->summary !!}
        <br>
        {!! $news->content !!}
      </div>
      {{-- <div class="text-center like_face">
        <div class="fb-like" data-href="https://developers.facebook.com/docs/plugins/" data-layout="standard" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
        <div class="fb-comments" data-href="https://developers.facebook.com/docs/plugins/comments#configurator" data-numposts="10" data-width="100%"></div>
      </div> --}}

    </section>
  </div>
</div>
</section>