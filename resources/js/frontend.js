jQuery(document).ready(function($){
  var $url = window.location.origin+'/';

  jQuery('.toggler-icon').on('click', 'a', function (e) {
    e.preventDefault();
    jQuery('#header .mobile-menu').addClass('active');
  });
  jQuery('#header .mobile-menu').on('click', 'span.close', function (e) {
    jQuery(this).parent().removeClass('active');
  });
  jQuery('#header .mobile-menu').on('click', '.nav-item .parent', function (e) {
    jQuery('#header .mobile-menu .nav-item ul.sub-menu').removeClass('active');
    jQuery(this).addClass('active');
    jQuery(this).parent().find('ul.sub-menu').slideToggle();
    jQuery(this).find('svg').toggleClass('spin');
  });

  jQuery('.lang_select #choose_lang').on('click', function(){
    $(this).parent().find('ul.lang_list').toggle();
  });
  function check_language(){
    $('.lang_selected').empty();
    lang = '.lang_'+$('#website_language').val();
    $('.lang_selected').append('<img src="'+ $('.lang_select ul.lang_list').find(lang).attr('src') +'" alt="language-selected">');
    return true;
  }
  check_language();

  var swiper = $('script[src="https://unpkg.com/swiper/swiper-bundle.min.js"]').length;
  
  if(swiper > 0){
    var swiperVer = new Swiper('.slide_vertical',{
      direction: 'vertical',
      autoplay:{
        delay:2500
      },
      loop: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
    });

    var swiperHo = new Swiper ('.swipe_horizontal',{
      direction: 'horizontal',
      loop: true,

      autoplay:{
        delay:3500
      },

      effect: 'coverflow', // or cube, fade
      grabCursor: true,
      cubeEffect: {
        shadow: true,
        slideShadows: true,
        shadowOffset: 20,
        shadowScale: 0.94,
      },

      flipEffect: {
        rotate: 30,
        slideShadows: false,
      },
      pagination: {
        el: '.swiper-pagination',
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      scrollbar: {
        el: '.swiper-scrollbar',
      },
    });

    var swiper = new Swiper('.slide_multi',{
      // slidesPerView: 4,
      autoplay:{
        delay:3500
      },
      autoplay: false,
      spaceBetween: 30,
      slidesPerGroup: 2,
      loop: true,
      loopFillGroupWithBlank: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        380: {
          slidesPerView: 1,
          spaceBetween: 10,
        },
        640: {
          slidesPerView: 2,
          spaceBetween: 15,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 25,
        },
        1024: {
          slidesPerView: 4,
          spaceBetween: 30,
        },
      }
    });
  }
  var jgallery = $('script[src="'+$url+'js/jgallery.min.js"]').length;
  if(jgallery > 0){
    $( '#gallery' ).jGallery({
      height: '600px',
      autostart: true,
      preloadAll: false,
    });
  }
  var lightbox = $('script[src="'+$url+'js/lc_lightbox.lite.min.js"]').length;
  if(lightbox > 0){
    lc_lightbox('.elem', {
      wrap_class: 'lcl_fade_oc',
      gallery : true, 
      thumb_attr: 'data-lcl-thumb', 
      skin: 'dark',
      txt_toggle_cmd: false,
      thumbs_nav: false,
    }); 
  }
  $('.popup').hover(function(){
    $(this).toggleClass('text-pop-up-top');
  });
  if($('script[src="https://unpkg.com/aos@2.3.1/dist/aos.js"]').length > 0){
    AOS.init();
  }


  // var introduce = $('section#introduce');
  // function MoveMe(picnic) {
  //     var currentX = $(introduce).css("background-position-x").replace("px","");

  //     var newX = parseInt(currentX) + 1;
  //     $(introduce).css("background-position-x", newX + "px");

  //     var wait = 50;
  //     setTimeout(function () { MoveMe(introduce); }, wait);
  //   }

  // MoveMe(introduce);

  jQuery('.main-intro .get-sub-service').on('mouseover', function(){
    jQuery(this).parent().find('div.sub-list').show();
  });
  jQuery('.main-intro div.sub-list').on('mouseleave', function(){
    jQuery(this).slideUp();
  });
  
  // jQuery('.main-intro .get-sub-service').on('mouseleave', function(){
  //   // if(jQuery(this).parent().find('div.sub-list:not(:hover)')) {
  //   //   console.log('not');
  //   //   setTimeout(function () {
  //   //     jQuery('.main-intro div.sub-list').slideUp();
  //   //   }, 200);
  //   // }
  //   if(jQuery(this).parent().find('div.sub-list').is(':hover')) {
  //     console.log('hover');
  //     jQuery('.main-intro div.sub-list').show();
  //   }
  // });
  jQuery('#our-team .nhanvien_list').on('mouseover','.profile', function(){
    $(this).find('.name').hide();
    $(this).find('.visit').slideDown();
  });

  jQuery('#our-team .nhanvien_list').on('mouseleave','.profile', function(){
    $(this).find('.name').slideDown();
    $(this).find('.visit').hide();
  });

});