@php
$blog_categories = Theme::bind('blog_categories');
$services = Theme::bind('services');
$lang = session('hatlaw_language')??'vi';
@endphp
<!-- contact 3 columns-->
<p class="line"></p>
<!-- <section class="sub-footer p-md-2 p-1">
  <div class="container">
  <div class="row">
    <div class="col-6 consulting flex-nowrap text-center">
        <h5 class="d-inline-flex mr-1 mr-sm-3">{{ trans('frontend.consulting') }}</h5>
        {{ Form::text('consulting','',['id' => 'consulting', 'class' => 'footer-input', 'placeholder' => trans('frontend.phone_now') ]) }}
    </div>
    <div class="col-6 newletters text-center">
      <h5 class="d-inline-flex mr-1 mr-sm-3">{{ trans('frontend.newsletter') }}</h5>
      {{ Form::text('newsletter','',['id' => 'newsletter', 'class' => 'footer-input','placeholder' => trans('frontend.email_now') ]) }}
    </div>
  </div>
  </div>
</section> -->

<footer id="footer">
  <section id="footer-info">
  <div class="container">
    <div class="row footer-info">
  		<div class="col-md-5 col-12 d-flex justify-content-center">
        <div class="company_info p-1 m-1 p-sm-3 w-100">
  				<div class="text-center" data-aos="{{ config('constant.aos')[rand(0,21)]}}">
  					<a class="logo" href="#"><img src="{{ url('storage/logo.png')}}" alt="logo" title="logo"></a>
  				</div>

  				<h5><i class="fas fa-map-marked-alt mr-2"></i>{{ Theme::bind('config')['company_add']??'' }}</h5>
          <h5><i class="fas fa-map-marked-alt mr-2"></i>Lot 50, 92 Road, Thai Hung Urban area, Nha Trang City, Khanh Hoa Province, Vietnam</h5>
  				<h5><i class="fas fa-envelope mr-2"></i><a class="white" href="mailto:{{ Theme::bind('config')['company_email']??'' }}?Subject=Hello">{{ Theme::bind('config')['company_email']??'' }}</a></h5>
        </div>
  		</div>

  		<div class="col-md-7 col-12 footer_link">
        <div class="row p-0 p-sm-3">
  			@php
          if(!empty($services)){
            echo "<div class='link col-12 col-md-6'><div class='link-title'><h3>".trans('frontend.service.name')."</h3></div>
                <div class='url'>";
            foreach($services as $service){
              if(!empty($service['sub'])){
                foreach($service['sub'] as $sub){
                  $name = (!empty($sub[$lang]['name']))?$sub[$lang]['name']:$sub['vi']['name'];
                  echo "<a href='".route('service',$sub['slug'])."'><i class='fas fa-angle-right'></i> ".$name."</a>";
                }

              }
            }
            echo '</div></div>';
          }

          if(!empty($blog_categories)){
            echo "<div class='link col-12 col-md-3'><div class='link-title'><h3>".trans('frontend.information')."</h3></div>
                <div class='url'>";
            foreach($blog_categories as $category){
              if($category->parent_id == 0){
                if($lang == 'en') echo "<a href='".route('blog.category',$category->slug)."'><i class='fas fa-angle-right'></i> ".$category->en_name??$category->name."</a>";
                else echo "<a href='".route('service',$category->slug)."'><i class='fas fa-angle-right'></i> ".$category->name."</a>";
              }
            }
            echo '</div></div>';
          }

          echo "<div class='link link_social col-12 col-md-3'><div class='link-title'><h3>".trans('frontend.social')."</h3></div>
            <div class='url'>
              <a href='https://www.facebook.com/profile.php?id=100046527083151'><i class='fab fa-facebook-f'></i></a>
              <a href='#'><i class='fab fa-google-plus-g'></i></a>
            </div></div>";

          //services
        @endphp
        </div>
  		</div>
    </div> <!-- footer-info -->
  </div> <!-- container -->
  </section>

  <section id="copyright">
  <div class="container p-2 p-md-3">
    <div class="row footer-bottom">
      <div class="col-12 text-center pt-md-2 pb-md-2">
        <span>Copyright © <script>document.write(new Date().getFullYear());</script> - {{ Theme::bind('config')['company_name']??config('constant.meta.default.company_name') }} - ALL RIGHTS RESERVED <a class="ml-3 small" href="https://nhatrangnet.net">Design by NhaTrangNet</a> </span>
      </div>
    </div>
  </div>
  </section>


  {{-- phone-icon --}}
  <div class="phonering-alo-phone phonering-alo-green phonering-alo-show" id="phonering-alo-phoneIcon" style="left: -50px; bottom: 150px; position: fixed;z-index: 1000">
      <div class="phonering-alo-ph-circle"></div>
        <div class="phonering-alo-ph-circle-fill"></div>
        <a href="tel:+84862017723"></a>
        <div class="phonering-alo-ph-img-circle">
          <a href="tel:+84862017723"></a>
          <a href="tel:+84862017723" class="pps-btn-img " title="Liên hệ">
             <img src="https://i.imgur.com/v8TniL3.png" alt="Liên hệ" width="50" onmouseover="this.src='https://i.imgur.com/v8TniL3.png';" onmouseout="this.src='https://i.imgur.com/v8TniL3.png';">
          </a>
        </div>
  </div>
  <a href="tel:+84862017723">
    <span class="footer_phone"><strong>{{ Theme::bind('config')['company_phone']??'' }}</strong></span>
  </a>
  {{-- end-phone-icon --}}


</footer>
  <!-- footer -->
</main>
<!-- Modal popup -->
<div class="modal fade d-none" id="popup_contact" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">{{ trans('frontend.contact_now') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {!!Form::open(['route'=>'index','name' => 'frm','id'=>'user-create-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-2 col-form-label">Tên</label>
          <div class="col-sm-10">
            <input type="email" class="form-control" id="inputEmail3" placeholder="name">
          </div>
        </div>

        <div class="form-group row">
         <label for="inputEmail3" class="col-sm-2 col-form-label">ĐTDĐ</label>
         <div class="col-sm-10">
           <input type="email" class="form-control" id="inputEmail3" placeholder="phone">
         </div>
       </div>
       <div class="form-group row">
         <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
         <div class="col-sm-10">
           <input type="email" class="form-control" id="inputEmail3" placeholder="email">
         </div>
       </div>
       {{-- {{ Form::submit_button() }} --}}
       {!! form::close() !!}
     </div>
   </div>
 </div>
</div>
