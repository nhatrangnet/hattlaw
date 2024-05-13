jQuery(document).ready(function($){
    // basic
    
    jQuery('.tooltip').on('hover', function(){
        alert(1);
    });

    $('.form_modal_focus').on('shown.bs.modal', function () {
      $('.form_modal_focus').trigger('focus')
    });

    
    jQuery('.summernote').summernote({
        height: 125,
        popover: {
            image: [],
            link: [],
            air: []
        },
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'undo']],
            ['fontsize', ['clear','fontsize','hr']],
            ['color', ['color','video','table']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['popovers', [,'fullscreen','codeview']],
        ],
        buttons: {
            // lfm: LFMButton // install laravel file maneger
        },
    });

    jQuery('.summernote_large').summernote({
        height: 300,
        focus: true,
        popover: {
            image: [],
            link: [],
            air: []
        },
        // toolbar: [
        //     ['style', ['bold', 'italic', 'underline', 'undo']],
        //     ['fontsize', ['fontsize','hr']],
        //     ['color', ['color','video','table']],
        //     ['para', ['ul', 'ol', 'paragraph']],
        //     ['popovers', ['lfm','fullscreen','codeview']],
        // ],
        buttons: {
            // lfm: LFMButton
        }
    });
    

    function check_language_admin(){
        $('.lang_selected').empty();
        lang = '.lang_'+$('#website_language_admin').val();
        $('.lang_selected').append('<img src="'+ $('.lang_select .dropdown-menu').find(lang).attr('src') +'" alt="language-selected">');
        return true;
    }
    check_language_admin();

    jQuery('.sidebar .nav-item .parent-link').on('click', function(){
        jQuery(this).parent().find('.sidebar_submenu').slideToggle(250);
    });
    var lightbox = $('script[src="https://webnoibo.local/js/lc_lightbox.lite.min.js"]').length;
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
    // end basic

    // tags multi select
    // $("#multi_select_row").bsMultiSelect();
    // $('.add_image_btn').on('click', function(){
    //     image_name = $(this).parent().data('name');
    //     $(this).parent().append('<div class="sub_img input-group mb-1"><input class="form-file col-md-10" name="'+image_name+'[]" type="file"><button class="btn btn-info remove_image_btn col-md-">Remove</button></div>');

    //     return false;
    // });
    // $('.multi_image_block').on('click', '.remove_image_btn', function(){
    //     $(this).parents('.sub_img').remove();
    //     return false;
    // });


    function loadImagebyGalleryCategory(category){
        if(window.location.href.indexOf("gallery-image") > -1) {
            $('.loading').show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "ajax_image_by_gallery_category",
                data: {
                    'category': category,
                },
                success: function(data) {
                    $('#ajax_galley_image').html(data)
                    // $('#show_report').attr('disabled',false);
                    // if(e == 0 && (partner_id != -1)){
                    //     $('#show_report').attr('disabled',true);
                    // }
                    // $('.order_search').html(e);
                    $('.loading').slideUp();
                },
                error: function (e) {
                    alert(e);
                }
            });
        }
    }
    $('#ajax_image_by_gallery_category').on('submit', function(){
        category = $('#select_gallery_category').find(":selected").val();
        // loadImagebyGalleryCategory($(this).serialize());
        loadImagebyGalleryCategory(category);
        return false;
    });

    // list admins
    $(function() {
        var userTable = $('#admins_list_table').DataTable({
            dom: 'Blprtip',
            processing: true,
            serverSide: true,
            order: [[ 6, "desc" ]],
            ajax:{
                url: 'get_list_admin_ajax',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.role_search = $('#role_search').val();
                    d.from_datetime = $('input[name=datetimepicker_from]').val();
                    d.to_datetime = $('input[name=datetimepicker_to]').val();
                }
            },
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'print'
            ],
            columns:
            [
                {data: 'DT_RowIndex'},
                {data: 'name' },
                {data: 'email' },
                {data: 'phone'},
                {data: 'role'},
                {data: 'status_data',class: 'text-center'},
                {data: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center minw-80'}
            ],
            fnDrawCallback:function(){

            }
        });
        $('#admins_search_form').on('submit', function(e) {
            userTable.draw();
            e.preventDefault();
        });

    });


    //list users
    jQuery(function() {
        var userTable = $('#users-table').DataTable({
            dom: 'Blprtip',
            processing: true,
            serverSide: true,
            ajax:{
                url: 'get_list_user_ajax',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.email = $('input[name=email]').val();
                    d.from_datetime = $('input[name=datetimepicker_from]').val();
                    d.to_datetime = $('input[name=datetimepicker_to]').val();
                }
            },
            buttons: [
                'copyHtml5',
                // 'excelHtml5',
                'csvHtml5',
                'print'
            ],
            order: [[ 4, "desc" ]],
            columns:
            [
                {data: 'DT_RowIndex', name: 'id' },
                {data: 'name', name: 'name' },
                {data: 'email', name: 'email' },
                {data: 'phone', name: 'phone' },
                {data: 'birthday', name: 'birthday' },
                {data: 'status_data',class: 'text-center'},
                // {data: 'updated_at', name: 'updated_at' },
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
            fnDrawCallback:function(){
                $('#reset-button').on('click', function() {
                    $('#users-table').bootstrapTable('destroy'); //Destroy bootstrap table
                    $('#users-table').bootstrapTable(); //Rebuild bootstrap table

                    //You can do it in one line
                    //$('#users-table').bootstrapTable('destroy').bootstrapTable();
                });
            }
        });
        $('#user-search-form').on('submit', function(e) {
            userTable.draw();
            e.preventDefault();
        });

        // $('#user-search-form').on('keyup', function(e) {
        //     userTable.draw();
        //     e.preventDefault();
        // });
    });

    //birthday user
    jQuery(function() {

        var userTable = $('#users_birthay_table').DataTable({
            dom: 'Blprtip',
            processing: true,
            serverSide: true,
            ajax:{
                url: 'get_list_birthday_user_ajax',
                data: function (d) {
                    d.time_birthday_search = $('#time_birthday_search').val();
                }
            },
            buttons: [
                'copyHtml5',
                // 'excelHtml5',
                'csvHtml5',
                'print'
            ],
            order: [[ 2, "asc" ]],
            columns:
            [
                {data: 'DT_RowIndex', name: 'id' },
                {data: 'name', name: 'name' },
                {data: 'birthday', name: 'birthday' },
                {data: 'phone', name: 'phone' },
                {data: 'address', name: 'address' },
                {data: 'status_data', name: 'status' },
            ],
            fnDrawCallback:function(){
                $('.loading').slideUp();
                $('#reset-button').on('click', function() {
                    $('#users_birthay_table').bootstrapTable('destroy'); //Destroy bootstrap table
                    $('#users_birthay_table').bootstrapTable(); //Rebuild bootstrap table

                    //You can do it in one line
                    //$('#users_birthay_table').bootstrapTable('destroy').bootstrapTable();
                });
            }
        });
        $('#time_birthday_search').on('change', function(e) {
            userTable.draw();
            e.preventDefault();
        });

    });
    // end tables list

    // blog category
    $(function() {
        var userTable = $('#blog-category-table').DataTable({
            dom: 'Blprtip',
            processing: true,
            serverSide: true,
            order: [[ 3, "desc" ]],
            ajax:{
                url: 'get_list_blog_category_ajax',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.from_datetime = $('input[name=datetimepicker_from]').val();
                    d.to_datetime = $('input[name=datetimepicker_to]').val();
                }
            },
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'print'
            ],
            columns:
            [
                {data: 'DT_RowIndex', name: 'id' },
                {data: 'name', name: 'name' },
                {data: 'status', name: 'status' ,class: 'text-center'},
                {data: 'updated_at', name: 'updated_at' },
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
            fnDrawCallback:function(){
            }
        });
        $('#blog-category-search-form').on('submit', function(e) {
            userTable.draw();
            e.preventDefault();
        });

    });

    // blog news
    $(function() {
        var userTable = $('#blog-news-table').DataTable({
            dom: 'Blprtip',
            processing: true,
            serverSide: true,
            order: [[ 3, "desc" ]],
            ajax:{
                url: 'get_list_blog_news_ajax',
                data: function (d) {
                    d.title = $('input[name=title]').val();
                    d.from_datetime = $('input[name=datetimepicker_from]').val();
                    d.to_datetime = $('input[name=datetimepicker_to]').val();
                }
            },
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'print'
            ],
            columns:
            [
                {data: 'DT_RowIndex'},
                {data: 'title' },
                {data: 'status',class: 'text-center'},
                {data: 'updated_at' },
                {data: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
            fnDrawCallback:function(){

            }
        });
        $('#blog-news-search-form').on('submit', function(e) {
            userTable.draw();
            e.preventDefault();
        });

    });

    // list tag
    $(function() {
        var userTable = $('#admin_tag_table').DataTable({
            dom: 'Blprtip',
            processing: true,
            serverSide: true,
            order: [[ 4, "desc" ]],
            ajax:{
                url: 'get_list_tag_ajax',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.from_datetime = $('input[name=datetimepicker_from]').val();
                    d.to_datetime = $('input[name=datetimepicker_to]').val();
                }
            },
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'print'
            ],
            columns:
            [
                {data: 'DT_RowIndex'},
                {data: 'name' },
                {data: 'status_data',class: 'text-center'},
                {data: 'number_post'},
                {data: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
            fnDrawCallback:function(){

            }
        });
        $('#blog-category-search-form').on('submit', function(e) {
            userTable.draw();
            e.preventDefault();
        });

    });

    // list role
    $(function() {
        var roleTable = $('#admin_role_table').DataTable({
            dom: 'Blprtip',
            processing: true,
            serverSide: true,
            order: [[ 3, "desc" ]],
            ajax:{
                url: 'get_list_role_ajax',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.from_datetime = $('input[name=datetimepicker_from]').val();
                    d.to_datetime = $('input[name=datetimepicker_to]').val();
                }
            },
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'print'
            ],
            columns:
            [
                {data: 'DT_RowIndex'},
                {data: 'name' },
                {data: 'status_data',class: 'text-center'},
                {data: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
            fnDrawCallback:function(){

            }
        });
        $('#role_search_form').on('submit', function(e) {
            roleTable.draw();
            e.preventDefault();
        });

    });
    // list gallery category
    $(function() {
        var roleTable = $('#admin_gallery_category_table').DataTable({
            dom: 'Blprtip',
            processing: true,
            serverSide: true,
            order: [[ 2, "desc" ]],
            ajax:{
                url: 'get_list_gallery_category_ajax',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.from_datetime = $('input[name=datetimepicker_from]').val();
                    d.to_datetime = $('input[name=datetimepicker_to]').val();
                }
            },
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'print'
            ],
            columns:
            [
                {data: 'DT_RowIndex'},
                {data: 'name' },
                {data: 'status',class: 'text-center'},
                {data: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
            fnDrawCallback:function(){

            }
        });
        $('#role_search_form').on('submit', function(e) {
            roleTable.draw();
            e.preventDefault();
        });

    });
    
    // list service
    $(function() {
        var roleTable = $('#admin_service_table').DataTable({
            dom: 'Blprtip',
            processing: true,
            serverSide: true,
            order: [[ 1, "desc" ]],
            ajax:{
                url: 'get_list_service_ajax',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.from_datetime = $('input[name=datetimepicker_from]').val();
                    d.to_datetime = $('input[name=datetimepicker_to]').val();
                }
            },
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'print'
            ],
            columns:
            [
                {data: 'DT_RowIndex'},
                {data: 'name' },
                {data: 'status_data',class: 'text-center'},
                // {data: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
            fnDrawCallback:function(){

            }
        });
        $('#service_search_form').on('submit', function(e) {
            roleTable.draw();
            e.preventDefault();
        });

    });

// list review
    $(function() {
        var userTable = $('#admin_review_table').DataTable({
            dom: 'Blprtip',
            processing: true,
            serverSide: true,
            order: [[ 5, "desc" ]],
            ajax:{
                url: 'get_list_review_ajax',
                data: function (d) {
                    d.author_name = $('input[name=author_name]').val();
                    d.from_datetime = $('input[name=datetimepicker_from]').val();
                    d.to_datetime = $('input[name=datetimepicker_to]').val();
                }
            },
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'print'
            ],
            columns:
            [
                {data: 'DT_RowIndex'},
                {data: 'author_name' },
                {data: 'text'},
                {data: 'rating', class:'text-center'},
                {data: 'status_data',class: 'text-center'},
                {data: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
            fnDrawCallback:function(){

            }
        });
        $('#blog-category-search-form').on('submit', function(e) {
            userTable.draw();
            e.preventDefault();
        });

    });

    //shop
    //brand
    $(function() {
        var roleTable = $('#brand_table').DataTable({
            dom: 'Blprtip',
            processing: true,
            serverSide: true,
            order: [[ 3, "desc" ]],
            ajax:{
                url: 'get_list_brand_ajax',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.from_datetime = $('input[name=datetimepicker_from]').val();
                    d.to_datetime = $('input[name=datetimepicker_to]').val();
                }
            },
            buttons: [
                'copyHtml5',
                'csvHtml5',
                'print'
            ],
            columns:
            [
                {data: 'DT_RowIndex'},
                {data: 'name' },
                {data: 'status_data',class: 'text-center'},
                {data: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
            fnDrawCallback:function(){

            }
        });
        $('#service_search_form').on('submit', function(e) {
            roleTable.draw();
            e.preventDefault();
        });

    });
});
