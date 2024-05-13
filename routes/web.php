<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
Route::get('change-language/{language}', 'Frontend\HomeController@changeLanguage')->name('change_language');

Route::group(['middleware' => 'locale'],function(){
    Route::get('/', 'Frontend\HomeController@index')->name('index');
    Route::get('/introduce', 'Frontend\HomeController@introduce')->name('introduce');
    Route::get('/our-team', 'Frontend\HomeController@our_team')->name('our.team');

    Route::get('/userboard', 'Frontend\HomeController@userboard')->name('userboard');
    Route::get('/contact', 'Frontend\ContactController@index')->name('contact');
    Route::post('/contact-save', 'Frontend\ContactController@save')->name('contact.save');


    Route::get('category/{category_slug}', 'Frontend\HomeController@category')->name('blog.category');

    Route::get('service/{service_slug}', 'Frontend\HomeController@service')->name('service');
    Route::get('news/{news_slug}', 'Frontend\HomeController@show_news')->name('blog.news');
    
    Route::post('post_get_sub_service', 'Frontend\HomeController@post_get_sub_service')->name('post_get_sub_service');
});


Route::get('gallery/{category_id?}', 'Frontend\HomeController@gallery')->name('gallery');

Route::get('user','Backend\DashboardController@index')->name('user.index');

Route::get('get_list_birthday_user_ajax','Frontend\HomeController@get_list_birthday_user_ajax');

Route::resource('cart', 'Frontend\CartController', [
    'names' => [
        'index' => 'cart.index',
        'create' => 'cart.create',
        'store' => 'cart.store',
        'edit' => 'cart.edit',
        // 'update' => 'cart.update',
    ],
]);

Route::get('cart/history','Frontend\CartController@history')->name('cart.history');
Route::get('get_list_order_ajax','Frontend\CartController@get_list_order_ajax');
Route::get('change_customer_list_ajax','Frontend\CartController@change_customer_list_ajax');
Route::post('add_to_cart','Frontend\CartController@addToCart');
Route::post('add_shipping_charge','Frontend\CartController@addShippingCharge');
Route::post('add_tax','Frontend\CartController@addTax');
Route::post('add_discount_percent','Frontend\CartController@addDiscountPercent');
Route::get('print_order/{order_id}','Frontend\CartController@printOrder')->name('print.order');

Route::post('update_cart_status','Frontend\CartController@update_cart_status');

Route::post('update_cart_product','Frontend\CartController@update_cart_product');
Route::post('delete_cart_product','Frontend\CartController@delete_cart_product');
//User system
Route::resource('user', 'Frontend\UserController', [
    'names' => [
        'index' => 'user.index',
        'create' => 'user.create',
        'store' => 'user.store',
        'edit' => 'user.edit',
        // 'update' => 'user.update',
    ]
]);
Route::post('update_user','Frontend\UserController@update_user');
Route::get('get_list_user_ajax','Frontend\UserController@get_list_user_ajax')->name('admin.get_list_user_ajax');

Route::resource('store', 'Frontend\StoreController', [
    'names' => [
        'index' => 'store.index',
        'create' => 'store.create',
        'store' => 'store.store',
        'edit' => 'store.edit',
        'update' => 'store.update',
    ]
]);
Route::get('get_list_product_ajax','Frontend\StoreController@get_list_product_ajax');
Route::get('store_history','Frontend\StoreController@store_history')->name('store.history');
Route::get('store_statistic','Frontend\StoreController@store_statistic')->name('store.statistic');
Route::get('store_report','Frontend\StoreController@store_report')->name('store.report');
Route::post('store_report_save','Frontend\StoreController@store_report_save')->name('store.report_save');
Route::get('product_history/{product_id}','Frontend\StoreController@product_history')->name('product.history');
Route::get('get_statistic_store','Frontend\StoreController@getStatisticStore');

Route::get('dashboard/login', 'Backend\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('dashboard/login', 'Backend\AdminLoginController@login')->name('admin.login.submit');
Route::get('dashboard/logout', 'Backend\AdminLoginController@logout')->name('admin.logout');

Route::group(['prefix' => 'dashboard', 'middleware' => ['admin.locale','admin.checklogin:admin']],function(){
	Route::get('register','Backend\DashboardController@getRegister');
	Route::post('register','Backend\DashboardController@postRegister')->name('admin.register.submit');
	// Admin system
    Route::get('/', 'Backend\DashboardController@index')->name('admin.index');
    Route::resource('admin', 'Backend\DashboardController', [
        'names' => [
            'create' => 'admin.create',
            'store' => 'admin.store',
            'edit' => 'admin.edit',
            'update' => 'admin.update',
        ],
    ]);
    
    Route::get('admin', 'Backend\DashboardController@list_admins')->name('admin.list');
    Route::get('admin/{admin_id}/destroy/{option?}','Backend\DashboardController@destroy')->name('admin.destroy');
    Route::get('admin/{admin_id}/restore','Backend\DashboardController@restore')->name('admin.restore');
    Route::get('get_list_admin_ajax','Backend\DashboardController@get_list_admin_ajax')->name('admin.get_list_admin_ajax');


	Route::get('change-language-admin/{language}', 'Backend\DashboardController@changeLanguage')->name('admin.change_language');
    Route::get('adminboard', 'Backend\DashboardController@adminboard')->name('adminboard');
    Route::get('config','Backend\DashboardController@config')->name('admin.config');
    Route::post('config/save','Backend\DashboardController@config_save')->name('admin.config.save');
    Route::get('introduce','Backend\DashboardController@introduce')->name('admin.introduce');
    Route::post('introduce/save','Backend\DashboardController@introduce_save')->name('admin.introduce.save');
    Route::resource('gallery-category', 'Backend\Gallery\CategoryController', [
        'names' => [
            'index' => 'admin.gallery_category.index',
            'create' => 'admin.gallery_category.create',
            'store' => 'admin.gallery_category.store',
            'edit' => 'admin.gallery_category.edit',
            'update' => 'admin.gallery_category.update',
        ]
    ]);
    Route::get('gallery-category/{user_id}/destroy/{option}','Backend\UserController@destroy')->name('admin.gallery_category.destroy');
    Route::get('gallery-category/{user_id}/restore','Backend\UserController@restore')->name('admin.gallery_category.restore');
    Route::get('get_list_gallery_category_ajax','Backend\Gallery\CategoryController@get_list_gallery_category_ajax')->name('admin.get_list_gallery_category_ajax');


    Route::get('role/{role_id}/destroy/{option}','Backend\RoleController@destroy')->name('admin.role.destroy');

    Route::get('role/{role_id}/restore','Backend\RoleController@restore')->name('admin.role.restore');

    Route::resource('gallery-image', 'Backend\Gallery\ImageController', [
        'names' => [
            'index' => 'admin.galleryimage.index',
            'create' => 'admin.galleryimage.create',
            'store' => 'admin.galleryimage.store',
            'edit' => 'admin.galleryimage.edit',
            'update' => 'admin.galleryimage.update',
        ]
    ]);
    Route::post('ajax_image_by_gallery_category','Backend\Gallery\ImageController@ajax_image_by_gallery_category')->name('admin.ajax_image_by_gallery_category');
    //User system
    Route::resource('user', 'Backend\UserController', [
        'names' => [
            'index' => 'admin.user.list',
            'create' => 'admin.user.create',
            'store' => 'admin.user.store',
            'edit' => 'admin.user.edit',
            'update' => 'admin.user.update',
        ]
    ]);
    Route::get('user/{user_id}/destroy/{option}','Backend\UserController@destroy')->name('admin.user.destroy');
    Route::get('user/{user_id}/restore','Backend\UserController@restore')->name('admin.user.restore');
    Route::get('get_list_user_ajax','Backend\UserController@get_list_user_ajax')->name('admin.get_list_user_ajax');

    //Service system
    Route::resource('service', 'Backend\ServiceController', [
        'names' => [
            'index' => 'admin.service.list',
            'create' => 'admin.service.create',
            'store' => 'admin.service.store',
            'edit' => 'admin.service.edit',
            'update' => 'admin.service.update',
        ]
    ]);
    Route::get('service/{service_id}/destroy/{option}','Backend\ServiceController@destroy')->name('admin.service.destroy');
    Route::get('service/{service_id}/restore','Backend\ServiceController@restore')->name('admin.service.restore');
    Route::get('get_list_service_ajax','Backend\ServiceController@get_list_service_ajax')->name('get_list_service_ajax');

    //blog_category system
    Route::resource('blog-category', 'Backend\Blog\CategoryController', [
        'names' => [
            'index' => 'admin.blog_category.list',
            'create' => 'admin.blog_category.create',
            'store' => 'admin.blog_category.store',
            'edit' => 'admin.blog_category.edit',
            'update' => 'admin.blog_category.update',
        ]
    ]);
    Route::get('blog-category/{category_id}/destroy/{option}','Backend\Blog\CategoryController@destroy')->name('admin.blog_category.destroy');
    Route::get('get_list_blog_category_ajax','Backend\Blog\CategoryController@get_list_blog_category_ajax')->name('admin.get_list_blog_category_ajax');
    Route::get('blog-category/{category_id}/restore','Backend\Blog\CategoryController@restore')->name('admin.blog_category.restore');

    // blog_news system
    Route::resource('blog-news', 'Backend\Blog\NewsController', [
        'names' => [
            'index' => 'admin.blog_news.list',
            'create' => 'admin.blog_news.create',
            'store' => 'admin.blog_news.store',
            'edit' => 'admin.blog_news.edit',
            'update' => 'admin.blog_news.update',
        ]
    ]);

    Route::get('blog-news/{category_id}/destroy','Backend\Blog\NewsController@destroy')->name('admin.blog_news.destroy');
    Route::get('get_list_blog_news_ajax','Backend\Blog\NewsController@get_list_blog_news_ajax')->name('admin.get_list_blog_news_ajax');
    Route::get('blog-news/{category_id}/restore','Backend\Blog\NewsController@restore')->name('admin.blog_news.restore');

    Route::resource('tag', 'Backend\TagController', [
        'names' => [
            'index' => 'admin.tag.list',
            'create' => 'admin.tag.create',
            'store' => 'admin.tag.store',
            'edit' => 'admin.tag.edit',
            'update' => 'admin.tag.update',
        ]
    ]);
    Route::get('tag/{tag_id}/destroy/{option}','Backend\TagController@destroy')->name('admin.tag.destroy');
    Route::get('tag/{tag_id}/restore','Backend\TagController@restore')->name('admin.tag.restore');

    Route::get('get_list_tag_ajax','Backend\TagController@get_list_tag_ajax')->name('admin.get_list_tag_ajax');

    Route::resource('role', 'Backend\RoleController', [
        'names' => [
            'index' => 'admin.role.list',
            'create' => 'admin.role.create',
            'store' => 'admin.role.store',
            'edit' => 'admin.role.edit',
            'update' => 'admin.role.update',
        ]
    ]);
    Route::get('role/{role_id}/destroy/{option}','Backend\RoleController@destroy')->name('admin.role.destroy');
    Route::get('get_list_role_ajax','Backend\RoleController@get_list_role_ajax')->name('admin.get_list_role_ajax');
    Route::get('role/{role_id}/restore','Backend\RoleController@restore')->name('admin.role.restore');

    //Shop / Store
    Route::resource('product-category', 'Backend\Shop\CategoryController', [
        'names' => [
            'index' => 'admin.product-category.list',
            'create' => 'admin.product-category.create',
            'store' => 'admin.product-category.store',
            'edit' => 'admin.product-category.edit',
            'update' => 'admin.product-category.update',
        ]
    ]);
    Route::get('product-category/{role_id}/destroy/{option}','Backend\Shop\CategoryController@destroy')->name('admin.product-category.destroy');
    Route::get('get_list_product_category_ajax','Backend\Shop\CategoryController@get_list_product_category_ajax')->name('admin.get_list_product_category_ajax');
    Route::get('product-category/{role_id}/restore','Backend\Shop\CategoryController@restore')->name('admin.product-category.restore');

    Route::resource('product', 'Backend\Shop\ProductController', [
        'names' => [
            'index' => 'admin.product.list',
            'create' => 'admin.product.create',
            'store' => 'admin.product.store',
            'edit' => 'admin.product.edit',
            'update' => 'admin.product.update',
        ]
    ]);
    Route::get('product/{product_id}/destroy/{option}','Backend\Shop\ProductController@destroy')->name('admin.product.destroy');
    Route::get('get_list_product_ajax','Backend\Shop\ProductController@get_list_product_ajax')->name('admin.get_list_product_ajax');
    Route::get('product/{product_id}/restore','Backend\Shop\ProductController@restore')->name('admin.product.restore');
    Route::get('product/{product_id}/delete-image/{image}','Backend\Shop\ProductController@delete_product_image')->name('admin.delete_product_image');

    Route::resource('brand', 'Backend\Shop\BrandController', [
        'names' => [
            'index' => 'admin.brand.list',
            'create' => 'admin.brand.create',
            'store' => 'admin.brand.store',
            'edit' => 'admin.brand.edit',
            'update' => 'admin.brand.update',
        ]
    ]);
    Route::get('brand/{brand_id}/destroy','Backend\Shop\BrandController@destroy')->name('admin.brand.destroy');
    Route::get('get_list_brand_ajax','Backend\Shop\BrandController@get_list_brand_ajax')->name('admin.get_list_brand_ajax');
    Route::get('brand/{brand_id}/restore','Backend\Shop\BrandController@restore')->name('admin.brand.restore');



    Route::resource('reviews', 'Backend\ReviewController', [
        'names' => [
            'index' => 'admin.reviews.list',
            'create' => 'admin.reviews.create',
            'store' => 'admin.reviews.store',
            'edit' => 'admin.reviews.edit',
            'update' => 'admin.reviews.update',
        ]
    ]);
    Route::get('reviews/{review_id}/destroy','Backend\ReviewController@destroy')->name('admin.reviews.destroy');
    Route::get('get_list_review_ajax','Backend\ReviewController@get_list_review_ajax')->name('admin.get_list_review_ajax');
    Route::get('reviews/{review_id}/restore','Backend\ReviewController@restore')->name('admin.reviews.restore');


});
