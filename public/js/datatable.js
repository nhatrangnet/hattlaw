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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/datatable.js":
/*!***********************************!*\
  !*** ./resources/js/datatable.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

try {
  // window.$ = window.jQuery = require('jquery');
  // window.JSZip = require("jszip");e
  // require( "pdfmake" );

  /*
  require( 'datatables.net-bs4' );
  require( 'datatables.net-buttons-bs4' );
  require( 'datatables.net-buttons/js/buttons.colVis.js' );
  require( 'datatables.net-buttons/js/buttons.flash.js' );
  require( 'datatables.net-buttons/js/buttons.html5.js' );
  require( 'datatables.net-buttons/js/buttons.print.js' );
  // require( 'datatables.net-colreorder-bs4' );
  // require( 'datatables.net-fixedcolumns-bs4' );
  require( 'datatables.net-responsive-bs4' );
  // require( 'datatables.net-rowreorder-bs4' );
  // require( 'datatables.net-scroller-bs4' );
  require( 'datatables.net-keytable' );
  require( 'datatables.net-rowgroup' );
  
  */
  jQuery.fn.dataTable.Api.register('sum()', function () {
    return this.flatten().reduce(function (a, b) {
      if (typeof a === 'string') {
        a = a.replace(/[^\d.-]/g, '') * 1;
      }

      if (typeof b === 'string') {
        b = b.replace(/[^\d.-]/g, '') * 1;
      }

      return a + b;
    }, 0);
  });
  /*
  jQuery(function () {
      $('#datetimepicker_from').datetimepicker({
          format: 'DD-MM-YYYY HH:mm:ss',
      });
      $('#datetimepicker_to').datetimepicker({
          useCurrent: false,
          format: 'DD-MM-YYYY HH:mm:ss',
      });
      $("#datetimepicker_from").on("change.datetimepicker", function (e) {
      	$('.datetimepicker_type').prop('checked',true);
          $('#datetimepicker_to').datetimepicker('minDate', e.date);
      });
      $("#datetimepicker_to").on("change.datetimepicker", function (e) {
      	$('.datetimepicker_type').prop('checked',true);
          $('#datetimepicker_from').datetimepicker('maxDate', e.date);
      });
      $('#birthday').datetimepicker({
          useCurrent: false,
          format: 'DD-MM-YYYY',
      });
      $('.report_time').on('change', function(){
      	$('.report_time_type').prop('checked',true);
      })
  });
  */
  //validate

  jQuery(".form-validation").validate({
    ignore: ":hidden, [contenteditable='true']:not([name])"
  });
  jQuery('.form-validation').find('.required').addClass('border-danger');
  jQuery('.form-validation').find('.required').parent().find('label').append('<span class="text-danger font-italic small"> (*)</span>');
  var elementForm = $('.form-validation .border-danger');

  var checkInputForm = function checkInputForm() {
    if (elementForm[0]) {
      //check elementForm exists on page
      if ($(elementForm).val().length > 3) {
        $(elementForm).removeClass('border-danger');
        $(elementForm).addClass('border-primary');
      }
    }
  };

  elementForm.on('keyup keydown keypress change paste', function () {
    checkInputForm();
  });
  checkInputForm(); //end validate

  $('.disable_multi_submit').on("submit", function () {
    $(this).find("button[type='submit']").attr('disabled', true);
    return true;
  });
  $('.disable_multi_submit').find('#reset-button').on('click', function () {
    $(this).bootstrapTable('destroy').bootstrapTable();
  });

  window.next_confirm = function (e) {
    if (window.confirm("Are you sure?")) {
      return true;
    }

    return false;
  };

  window.delete_confirm = function (e) {
    if (window.confirm("Are you sure to delete?")) {
      return true; // location.href = this.href;
    }

    return false;
  };

  window.restore_confirm = function (e) {
    if (window.confirm("Are you sure to retore?")) {
      return true;
    }

    return false;
  };
} catch (e) {}

/***/ }),

/***/ 2:
/*!*****************************************!*\
  !*** multi ./resources/js/datatable.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/lynguyen/1live/law/resources/js/datatable.js */"./resources/js/datatable.js");


/***/ })

/******/ });