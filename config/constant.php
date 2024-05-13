<?php
/**
 * This is the file use to create any available have not change
 */
return [
	"main" => [
		"app_name" => env('APP_NAME', 'HattConsulting'),
    "default_title" => "HattConsulting website",
	],
	'redis_expire_day' => 3,
	'item_per_page' => 8,
	'max_image_width' => 1367,
	'slide' => '1321 x 530',
	'news_image' => '990 x 440',
	'no-image' => 'basic/no-image.svg',
	'template_not_found' => 'Template default not found',
	'DATE_TIME_FORMAT' => 'Y-m-d H:i:s',
	"meta" => [
		"default" => [
			"keywords" => env('DEFAULT_KEYWORD', 'HattConsulting, tu van luat nha trang'),
			"description" => env('DEFAULT_DES', 'Default des'),
			"robot" => env('DEFAULT_ROBOT','index, follow'),
			"author" => env('DEFAULT_AUTHOR', 'NhaTrangNet.net'),
			"company_name" => env('APP_NAME', 'NhaTrangNet.net'),
		]
	],

	"status" => [
		"on" => 1,
		"off" => 0,
		"default" => [
			"title" => "Trạng Thái",
			"attribute" => [
				[
					'id' => 1,
					'name' => "Hiện Thị"
				],
				[
					'id' => 2,
					'name' => "Không Hiện Thị"
				]
			]
		],
		"custom" => [
			"title" => "Trạng Thái",
			"attribute" => [
				1 => "Hiện Thị",
				2 => "Ẩn Tin",
				3 => "Đợi Duyệt",
				4 => "Từ Chối",
			]
		]

	],

	"permissions" => [
		'file' => 644,
		'folder' => 755,
		"content" => [ //DO NOT CHANGE VALUE
			'user' => 'user',
			'role' => 'role',
			'config' => 'config',
			'category' => 'category',
			'news' => 'news',
			'product' => 'product',
			'product_category' => 'product_category',
            'service' => 'service',
            'brand' => 'brand',
		],
		'role' => [
			'super_admin' => 'super_admin',
			'admin' => 'admin',
			'editor' => 'editor',
			'nhanvien' => 'nhanvien',
		],
	],

	'image' => [
		'small' => 250,
		'medium' => 680,
		'large' => 1280,
		'default' => '/default',
		'admin' => '/admin',
		'user' => '/user',
		'avatar' => '/avatar',
		'blognews' => '/blog/news',
		'blogcat' => '/blog/category',
		'service' => '/service',
		'category' => '/category',
		'product' => '/product',
		'product_slide' => '/product-slide',
		'brand' => '/brand',
		'gallerycat' => '/gallery/category',
		'gallery' => '/gallery',
		'review' => '/review',
	],
	'database' => [
		'admin' => 'admins',
		'service' => 'services',
		'user' => 'users',
		'role' => 'roles',
		'blog_news' => 'blog_news',
		'blog_categories' => 'blog_categories',
		'role_admin' => 'role_admin',
		'product_category' => 'categories',
		'product' => 'products',
		'product_images' => 'product_images',
		'brand' => 'brands',
		'order' => 'orders',
		'order_product' => 'order_product',
		'order_history' => 'order_history',
	],
	'order_status' => [
		'on_hold' => 'Tạo vận đơn',
		'processing' => 'Đang xử lý ',
		'finish' => 'Hoàn thành',
		// 'return' => '',
		'fail' => 'Trả hàng',
		'change' => 'Đổi hàng',
	],

	'aos' => [
		'fade-up',
		'fade-down',
		'fade-right',
		'fade-left',
		'fade-up-right',
		'fade-up-left',
		'fade-down-right',
		'fade-down-left',
		'flip-left',
		'flip-right',
		'flip-up',
		'flip-down',
		'zoom-in',
		'zoom-in-up',
		'zoom-in-down',
		'zoom-in-left',
		'zoom-in-right',
		'zoom-out',
		'zoom-out-up',
		'zoom-out-down',
		'zoom-out-right',
		'zoom-out-left'
	]
];
