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


jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
return this.flatten().reduce( function ( a, b ) {
	if ( typeof a === 'string' ) {
		a = a.replace(/[^\d.-]/g, '') * 1;
	}
	if ( typeof b === 'string' ) {
		b = b.replace(/[^\d.-]/g, '') * 1;
	}

	return a + b;
}, 0 );
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
var checkInputForm = function() {
    if(elementForm[0]){ //check elementForm exists on page
        if( $(elementForm).val().length > 3){
            $(elementForm).removeClass('border-danger');
            $(elementForm).addClass('border-primary');
        }
    }
};
elementForm.on('keyup keydown keypress change paste', function() {
    checkInputForm();
});
checkInputForm();
//end validate

$('.disable_multi_submit').on("submit",function(){
    $(this).find("button[type='submit']").attr('disabled',true);
    return true;
});
$('.disable_multi_submit').find('#reset-button').on('click', function() {
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
        return true;
        // location.href = this.href;
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