<section class="sidebar-sticky">
  <ul class="nav flex-column">
    <li class="nav-item-header">{{ trans('menu.dashboard')}}</li>
    {{-- <li class="nav-item {!! getMenuActive(['*/dashboard']) !!}">
      <a class="nav-link parent-link" href="#">
        <i class="fas fa-tasks"></i>
        <span>{{ trans('menu.manager')}}</span>
        <i class="fas fa-angle-left float-right caret"></i>
      </a>
      <ul class="nav nav-treeview sidebar_submenu">
        <li class="nav-item-sub{!! getMenuActive(['*/dashboard/order']) !!}">
          <a href="{{route('cart.index')}}" class="nav-link ">
            <span class="ml-1">{{ trans('menu.order.name')}}</span>
          </a>
        </li>
      </ul>
    </li> --}}
    
    @if(check_admin_logged_with_permiss(Session::get('admin'), ['service_view']))
    {{-- <li class="nav-item {!! getMenuActive(['*/service','*/service/create']) !!}">
      <a class="nav-link parent-link" href="#">
        <span>{{ trans('menu.service.name')}}</span>
        <i class="fas fa-angle-left float-right caret"></i>
      </a>
      <ul class="nav nav-treeview sidebar_submenu">
        <li class="nav-item-sub {!! getMenuActive(['*/service']) !!}">
          <a href="{{ route('admin.service.list') }}" class="nav-link">
            <span class="ml-1">{{ trans('menu.service.list')}}</span>
          </a>
        </li>
        <li class="nav-item-sub{!! getMenuActive(['*/service/create']) !!}">
          <a href="{{route('admin.service.create')}}" class="nav-link ">
            <span class="ml-1">{{ trans('menu.service.create')}}</span>
          </a>
        </li>
      </ul>
    </li> --}}
    <li class="nav-item {!! getMenuActive(['*/service']) !!}">
      <a class="nav-link" href="{!! route('admin.service.list')!!}">
        <i class="fab fa-get-pocket"></i>{{ trans('menu.service.name')}}<i class="fa fa-angle-left float-right caret"></i>
      </a>
    </li>
    @endif
    @if(check_admin_logged_with_permiss(Session::get('admin'), ['user_view']))
    {{-- <li class="nav-item {!! getMenuActive(['*/user','*/user/create']) !!}">
      <a class="nav-link parent-link" href="#">
        <span>{{ trans('menu.user.name')}}</span>
        <i class="fas fa-angle-left float-right caret"></i>
      </a>
      <ul class="nav nav-treeview sidebar_submenu">
        <li class="nav-item-sub {!! getMenuActive(['*/user']) !!}">
          <a href="{{ route('admin.user.list') }}" class="nav-link">
            <span class="ml-1">{{ trans('menu.user.list')}}</span>
          </a>
        </li>
        <li class="nav-item-sub{!! getMenuActive(['*/user/create']) !!}">
          <a href="{{route('admin.user.create')}}" class="nav-link ">
            <span class="ml-1">{{ trans('menu.user.create')}}</span>
          </a>
        </li>
      </ul>
    </li> --}}
    <li class="nav-item {!! getMenuActive(['*/user']) !!}">
      <a class="nav-link" href="{!! route('admin.user.list')!!}">
        <i class="fa fa-user"></i>{{ trans('menu.user.name')}}<i class="fa fa-angle-left float-right caret"></i>
      </a>
    </li>
    

    @endif
    @if(check_admin_logged_with_permiss(Session::get('admin'), ['admin_view']))
    <li class="nav-item {!! getMenuActive(['*/admin']) !!}">
      <a class="nav-link" href="{!! route('admin.list')!!}">
        <i class="fas fa-user-cog"></i>{{ trans('menu.admin.sub')}}<i class="fa fa-angle-left float-right caret"></i>
      </a>
    </li>
    @endif

    {{-- Category --}}
    <li class="nav-item {!! getMenuActive(['*/blog-category','*/blog-category/create']) !!}">
      <a class="nav-link parent-link" href="#">
        <i class="far fa-newspaper"></i><span>{{ trans('menu.news.name')}}</span>
        <i class="fa fa-angle-left float-right caret"></i>
      </a>
      <ul class="nav nav-treeview sidebar_submenu">
        @if(check_admin_logged_with_permiss(Session::get('admin'), ['category_update']))
        <li class="nav-item-sub {!! getMenuActive(['*/blog-category']) !!}">
          <a href="{{ route('admin.blog_category.list') }}" class="nav-link">
            <span class="ml-1">{{ trans('menu.category.list')}}</span>
          </a>
        </li>
        @endif
        @if(check_admin_logged_with_permiss(Session::get('admin'), ['news_create']))
        <li class="nav-item-sub {!! getMenuActive(['*/blog-news']) !!}">
          <a href="{{ route('admin.blog_news.list') }}" class="nav-link">
            <span class="ml-1">{{ trans('menu.news.list')}}</span>
          </a>
        </li>
        @endif

      </ul>

    </li>
    <li class="nav-item {!! getMenuActive(['*/reviews']) !!}">
      <a href="{{ route('admin.reviews.list') }}" class="nav-link">
        <span class="ml-1">{{ __('Reviews') }}</span>
      </a>
    </li>
    {{-- tag --}}
    {{-- <li class="nav-item {!! getMenuActive(['*/tag','*/tag/create']) !!}">
      <a class="nav-link" href="{!! route('admin.tag.list')!!}">
        <i class="fas fa-tags"></i>{{ trans('menu.tag.name')}}<i class="fa fa-angle-left float-right caret"></i>
      </a>
    </li> --}}

    {{-- Shop / Store --}}
{{--     <li class="nav-item-header">{{ trans('menu.store.name')}}</li>
    @if(check_admin_logged_with_permiss(Session::get('admin'), ['product_view']))
    <li class="nav-item {!! getMenuActive(['*/product', '*/product/create']) !!}">
      <a class="nav-link" href="{!! route('admin.product.list')!!}">
        <i class="fab fa-get-pocket"></i>{{ trans('menu.product.name')}}<i class="fa fa-angle-left float-right caret"></i>
      </a>
    </li>
    @endif
    @if(check_admin_logged_with_permiss(Session::get('admin'), ['service_view']))
    <li class="nav-item {!! getMenuActive(['*/product-category']) !!}">
      <a class="nav-link" href="{!! route('admin.product-category.list')!!}">
        <i class="fab fa-get-pocket"></i>{{ trans('menu.product_category.name')}}<i class="fa fa-angle-left float-right caret"></i>
      </a>
    </li>
    @endif
    
    @if(check_admin_logged_with_permiss(Session::get('admin'), ['brand_view']))
    <li class="nav-item {!! getMenuActive(['*/brand', '*/brand/create']) !!}">
      <a class="nav-link" href="{!! route('admin.brand.list')!!}">
        <i class="fab fa-get-pocket"></i>{{ trans('menu.brand.name')}}<i class="fa fa-angle-left float-right caret"></i>
      </a>
    </li>
    @endif
 --}}
    {{-- Admin session --}}
    <li class="nav-item-header">{{ trans('menu.config')}}</li>
    @if(check_admin_logged_with_permiss(Session::get('admin'), ['config_update']))
    <li class="nav-item {!! getMenuActive(['*/config']) !!}">
      <a class="nav-link" href="{!! route('admin.config')!!}">
        <i class="fas fa-tools"></i>{{ trans('menu.config')}}<i class="fa fa-angle-left float-right caret"></i>
      </a>
    </li>
    @endif

    <li class="nav-item {!! getMenuActive(['*/introduce']) !!}">
      <a class="nav-link" href="{!! route('admin.introduce')!!}">
        <i class="far fa-comment-dots"></i>{{ trans('form.introduce')}}<i class="fa fa-angle-left float-right caret"></i>
      </a>
    </li>

    <li class="nav-item {!! getMenuActive(['*/gallery-category','*/gallery-image']) !!}">
      <a class="nav-link parent-link" href="#">
        <i class="fa fa-images"></i><span>Gallery</span>
        <i class="fa fa-angle-left float-right caret"></i>
      </a>
      <ul class="nav nav-treeview sidebar_submenu">
        <li class="nav-item-sub {!! getMenuActive(['*/gallery-category']) !!}">
          <a href="{{ route('admin.gallery_category.index') }}" class="nav-link">
            <span class="ml-1">{{ trans('menu.category.name')}}</span>
          </a>
        </li>
        <li class="nav-item-sub {!! getMenuActive(['*/gallery-image']) !!}">
          <a href="{{ route('admin.galleryimage.index') }}" class="nav-link ">
            <span class="ml-1">Image</span>
          </a>
        </li>
      </ul>
    </li>

   {{--  @if(check_admin_logged_with_permiss(Session::get('admin'), ['role_create','role_update']))
    <li class="nav-item {!! getMenuActive(['*/role']) !!}">
      <a class="nav-link" href="{!! route('admin.role.list')!!}">
        <i class="fas fa-user-tag"></i>{{ trans('menu.role.name')}}<i class="fa fa-angle-left float-right caret"></i>
      </a>
    </li>
    @endif
    @if(check_admin_logged_with_permiss(Session::get('admin')))
    <li class="nav-item {!! getMenuActive(['*/admin']) !!}">
      <a class="nav-link" href="{!! route('admin.list')!!}">
        <i class="fas fa-user-tie"></i>{{ trans('menu.admin.name')}}<i class="fa fa-angle-left float-right caret"></i>
      </a>
    </li>
    @endif --}}
  </ul>
</section>
