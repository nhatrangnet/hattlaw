@php
$services = Theme::bind('services');
$lang=session('hatlaw_language')??'vi';
@endphp
<div class="main-intro half-header row justify-content-center align-items-center position-relative">

    <div class="half-left d-flex justify-content-end">
        <article class="col-12 col-md-10 col-lg-8">
            <h2>{{ trans('frontend.consult.business') }}</h2>
            <p>{{ trans('frontend.consult.business_slogan') }}</p>
            <div class="show-sub-service">
                <button class="btn btn-primary get-sub-service"><i class="fas fa-plus"></i></button>
                <div class="sub-list">
                @php
                    foreach($services[1]['sub'] as $sub){
                        if(isset($sub[$lang]['name'])) $name = $sub[$lang]['name'];
                        else $name = $sub['vi']['name'];
                        echo '<a href="'.route('service', $sub['slug']).'" class="nav-link subservice"><i class="fas fa-angle-right"></i> '.$name.'</a>';
                    }
                @endphp
                </div>
            </div>

        </article>
    </div>
    <div class="half-right d-flex justify-content-start">
        <article class="col-12 col-md-10 col-lg-8 ">
            <h2>{{ trans('frontend.consult.individual') }}</h2>
            <p>{{ trans('frontend.consult.individual_slogan') }}</p>
            <div class="show-sub-service">
                <button class="btn btn-primary get-sub-service"><i class="fas fa-plus"></i></button>
                <div class="sub-list">
                @php
                    foreach($services[2]['sub'] as $sub){
                        if(isset($sub[$lang]['name'])) $name = $sub[$lang]['name'];
                        else $name = $sub['vi']['name'];
                        echo '<a href="'.route('service', $sub['slug']).'" class="nav-link subservice"><i class="fas fa-angle-right"></i> '.$name.'</a>';
                    }
                @endphp
                </div>
            </div>
        </article>
    </div>
</div>


<section id="introduce">
    <div class="container">
        <div class="row justify-content-center align-items-center flex-column">
            <div class="infor w-50">
                <h1 class="title text-center hidden-xs-down">{{ Theme::bind('config')['company_name'] ??config('constant.meta.default.company_name')}}</h1>

            </div>
            <div class="slogan w-100">
                <h4 class="red title text-center hidden-xs-down" scrollamount="2" scrolldelay="50" direction="right">{!! $slogan??Theme::bind('config')['metades'] !!}</h4>
            </div>
        </div>
    </div>
</section>
<p class="line"></p>

<section id="services">
    <div class="container">
        <div class="row services">
        @php
        if(!empty($services)){
            $i=0;
            foreach ($services as $service){
                if(!empty($service['sub'])){
                    foreach($service['sub'] as $sid => $sub){
                        if($i%2 == 0) $color_class = 'service_brown';
                        else $color_class = 'service_blue';
                        $name = (!empty($sub[$lang]['name']))?$sub[$lang]['name']:$sub['vi']['name'];
                        echo '<div class="col-md-4 col-6 text-center '.$color_class.' ">
                                <div class="box7 mx-auto">
                                    <img src="'.getImageLink(config('constant.image.service'),$sub['cover']).'" alt="'.$name.'">


                                    <a href="'.route('service', $sub['slug']).'" class="box-content">
                                        <h3>'.$name.'</h3>
                                    </a>
                                </div>
                            </div>';
                        $i++;
                    }
                }

            }
        }
        @endphp
        </div>
    </div>
</section>
<section id="statistic">
<div class="container">
    <div class="row counterup">
        <div class="col-6 col-md-3 p-0">
            <div class="counterup-item">
                <h3 class="counter">100 +</h3>
                <p>Dự án đã hoàn thành</p>
            </div>
        </div>
        <div class="col-6 col-md-3 p-0">
            <div class="counterup-item">
                <h3 class="counter">10 +</h3>
                <p>Luật sư chuyên nghiệp</p>
            </div>
        </div>
        <div class="col-6 col-md-3 p-0">
            <div class="counterup-item">
                <h3 class="counter">100 +</h3>
                <p>Khách hàng</p>
            </div>
        </div>
        <div class="col-6 col-md-3 p-0">
            <div class="counterup-item">
                <h3 class="counter">8 năm</h3>
                <p>Kinh nghiệm</p>
            </div>
        </div>
    </div>
</div>
</section>

{{-- customer slide --}}
@if(!empty($customer_list))
<p class="line"></p>
<section id="customer_list" class="container">
    <div class="row">
        <h3 class="text-center font-weight-bolder text-uppercase">{{trans('frontend.our_clients')}}</h3>
        <div class="swiper-container slide_multi">
            <div class="swiper-wrapper">
                @php
                foreach($customer_list as $customer){
                    echo "<div class='swiper-slide'>
                        <a href='".$customer['website']."'><img src='".$customer['avatar']."' alt='".$customer['name']."' title='".$customer['name']."' style='max-height: 100px'></a>
                    </div>";
                }
                @endphp
            </div>
            <div class="swiper-pagination"></div>

            <div class="swiper-button-prev"><i class="fas fa-arrow-circle-left"></i></div>
            <div class="swiper-button-next"><i class="fas fa-arrow-circle-right"></i></div>
        </div>
    </div>
</section>
@endif

{{-- customer review --}}
@if(!empty($customer_reviews))
<section id="customer_review" class="container">
    <div class="row">
        <h3 class="text-center font-weight-bolder text-uppercase">{{trans('frontend.review_clients')}}</h3>
        <div class="swiper-container slide_multi">
            <div class="swiper-wrapper">
                @php
                $default_avatar_image = url('images/basic/default-review-avatar.png');
                $favorite_image = url('images/basic/star.png');
                $google_icon = url('images/basic/google-icon.svg');

                foreach($customer_reviews as $review){
                    $favorite = "";
                    for($i=0; $i < $review->rating; $i++){
                        $favorite .= "<img src='".$favorite_image."' alt='favorite-star' title='favorite-star'>";
                    }

                    $url = check_valid_url($review->author_url)?$review->author_url:'#';
                    $avatar_img = !empty($review->profile_photo)?Storage::url('review/'.$review->profile_photo):$default_avatar_image;

                    echo "<div class='swiper-slide'>
                            <div class='wp-google-feedback'>
                                <div class='content'>
                                    <div class='favorite'>".$favorite."</div>
                                    <div class='text'>". nl2br($review->text)."</div>
                                    <img class='google-icon' src='".$google_icon."' alt='google-icon' title='google-icon'/>
                                </div>
                            </div>

                            <div class='wp-google-user'>
                                <div class='avatar'><img src='".$avatar_img."' alt='avatar' title='avatar'></div>
                                <div class='infor'>
                                    <a href='".$url."'>
                                        ".$review->author_name."
                                    </a>
                                </div>
                            </div>
                        </div>";
                }
                @endphp
            </div>
            <div class="swiper-pagination"></div>

            <div class="swiper-button-prev"><i class="fas fa-arrow-circle-left"></i></div>
            <div class="swiper-button-next"><i class="fas fa-arrow-circle-right"></i></div>
        </div>
    </div>
</section>
@endif


<p class="line"></p>

<section id="news" class="container">
    <div class="row">
        @foreach($list_news as $news)
        <div class="mt-3 mb-2 w-100">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <?php
                            $url = 'storage'.config('constant.image.blognews').'/thumb/medium_'.$news->image;
                            if(!file_exists($url)) $url ='images/basic/default-image.jpg';
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
    </div>
</section>
    {{-- gallery --}}
@if(!empty($category_list) && !empty($category_list[1]['image_list']))
<section id="gallery">
    <p class="line"></p>
    <h4 class="text-center text-uppercase text-danger">Gallery</h4>
    <div class="row">

        @foreach($category_list as $id => $category)
        @if(!empty($category['image_list']))
        <div class="col-md-3 col-sm-6 text-center">
            <div class="box7 mx-auto">
                <img src="{{ url('storage'.config('constant.image.gallery').'/'.$id.'/'.$category['image_list'][0]['image']) }}" alt="{{ $category['image_list'][0]['image'] }}">
                <h3 class="p-2" style="position: absolute;width: 100%; bottom: 35%;background: rgba(243, 245, 214, 0.8); ">{{ $category['name'] }}</h3>
                <div class="box-content p-3">
                <a href="{{ route('gallery', $id) }}" class="fa fa-search white"></a>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif
</section>
