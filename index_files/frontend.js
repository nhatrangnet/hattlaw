/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/frontend.js":
/*!**********************************!*\
  !*** ./resources/js/frontend.js ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

jQuery(document).ready(function ($) {
  var $url = window.location.origin + '/';
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
  jQuery('.lang_select #choose_lang').on('click', function () {
    $(this).parent().find('ul.lang_list').toggle();
  });

  function check_language() {
    $('.lang_selected').empty();
    lang = '.lang_' + $('#website_language').val();
    $('.lang_selected').append('<img src="' + $('.lang_select ul.lang_list').find(lang).attr('src') + '" alt="language-selected">');
    return true;
  }

  check_language();
  var swiper = $('script[src="https://unpkg.com/swiper/swiper-bundle.min.js"]').length;

  if (swiper > 0) {
    var _ref;

    var swiperVer = new Swiper('.slide_vertical', {
      direction: 'vertical',
      autoplay: {
        delay: 2500
      },
      loop: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true
      }
    });
    var swiperHo = new Swiper('.swipe_horizontal', {
      direction: 'horizontal',
      loop: true,
      autoplay: {
        delay: 3500
      },
      effect: 'coverflow',
      // or cube, fade
      grabCursor: true,
      cubeEffect: {
        shadow: true,
        slideShadows: true,
        shadowOffset: 20,
        shadowScale: 0.94
      },
      flipEffect: {
        rotate: 30,
        slideShadows: false
      },
      pagination: {
        el: '.swiper-pagination'
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
      },
      scrollbar: {
        el: '.swiper-scrollbar'
      }
    });
    var swiper = new Swiper('.slide_multi', (_ref = {
      // slidesPerView: 4,
      autoplay: {
        delay: 3500
      }
    }, _defineProperty(_ref, "autoplay", false), _defineProperty(_ref, "spaceBetween", 30), _defineProperty(_ref, "slidesPerGroup", 2), _defineProperty(_ref, "loop", true), _defineProperty(_ref, "loopFillGroupWithBlank", true), _defineProperty(_ref, "pagination", {
      el: '.swiper-pagination',
      clickable: true
    }), _defineProperty(_ref, "navigation", {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev'
    }), _defineProperty(_ref, "breakpoints", {
      380: {
        slidesPerView: 1,
        spaceBetween: 10
      },
      640: {
        slidesPerView: 2,
        spaceBetween: 15
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 25
      },
      1024: {
        slidesPerView: 4,
        spaceBetween: 30
      }
    }), _ref));
  }

  var jgallery = $('script[src="' + $url + 'js/jgallery.min.js"]').length;

  if (jgallery > 0) {
    $('#gallery').jGallery({
      height: '600px',
      autostart: true,
      preloadAll: false
    });
  }

  var lightbox = $('script[src="' + $url + 'js/lc_lightbox.lite.min.js"]').length;

  if (lightbox > 0) {
    lc_lightbox('.elem', {
      wrap_class: 'lcl_fade_oc',
      gallery: true,
      thumb_attr: 'data-lcl-thumb',
      skin: 'dark',
      txt_toggle_cmd: false,
      thumbs_nav: false
    });
  }

  $('.popup').hover(function () {
    $(this).toggleClass('text-pop-up-top');
  });

  if ($('script[src="https://unpkg.com/aos@2.3.1/dist/aos.js"]').length > 0) {
    AOS.init();
  } // var introduce = $('section#introduce');
  // function MoveMe(picnic) {
  //     var currentX = $(introduce).css("background-position-x").replace("px","");
  //     var newX = parseInt(currentX) + 1;
  //     $(introduce).css("background-position-x", newX + "px");
  //     var wait = 50;
  //     setTimeout(function () { MoveMe(introduce); }, wait);
  //   }
  // MoveMe(introduce);


  jQuery('.main-intro .get-sub-service').on('mouseover', function () {
    jQuery(this).parent().find('div.sub-list').show();
  });
  jQuery('.main-intro div.sub-list').on('mouseleave', function () {
    jQuery(this).slideUp();
  }); // jQuery('.main-intro .get-sub-service').on('mouseleave', function(){
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

  jQuery('#our-team .nhanvien_list').on('mouseover', '.profile', function () {
    $(this).find('.name').hide();
    $(this).find('.visit').slideDown();
  });
  jQuery('#our-team .nhanvien_list').on('mouseleave', '.profile', function () {
    $(this).find('.name').slideDown();
    $(this).find('.visit').hide();
  });
});

/***/ }),

/***/ 1:
/*!****************************************!*\
  !*** multi ./resources/js/frontend.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/lynguyen/1live/law/resources/js/frontend.js */"./resources/js/frontend.js");


/***/ })

/******/ });