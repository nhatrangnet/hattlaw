<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="ie=edge">

<title>{{ Theme::get('company_name') }}</title>
<meta charset="utf-8">
<meta name="copyright" content="{!! Theme::get('author') !!}" />
<meta name="author" content="NhaTrangNet" />
<meta name="keywords" content="{!! Theme::get('keywords') !!}">
<meta name="description" content="{!! Theme::get('description') !!}">
<meta name="robots" content="{!! Theme::get('robot') !!}" />

<meta http-equiv="content-language" content="vi"/>
<meta name="geo.region" content="VN" />
<meta name="geo.placename" content="Nha Trang" />
<meta name="geo.position" content="12.238791;109.196749" />
<meta name="ICBM" content="12.238791, 109.196749" />

<meta name="revisit-after" content=" days">
<meta name="DC.title"  content="{{ Theme::get('company_name') }}"/>
<meta name="dc.description" content="{!! Theme::get('description') !!}">
<meta name="dc.keywords" content="{!! Theme::get('keywords') !!}">
<meta name="dc.subject" content="{!! Theme::get('author') !!}">
<meta name="dc.created" content="2018-08-03">
<meta name="dc.publisher" content="{!! Theme::get('author') !!}">
<meta name="dc.rights.copyright" content="NhaTrangNet">
<meta name="dc.creator.name" content="NhaTrangNet">
<meta name="dc.creator.email" content="cnttnt@gmail.com">
<meta name="dc.identifier" content="">
<meta name="dc.language" content="vi-VN">

<link rel="apple-touch-icon" sizes="57x57" href="{{ url('storage/basic/favicons/apple-icon-57x57.png')}}">
<link rel="apple-touch-icon" sizes="60x60" href="{{ url('storage/basic/favicons/apple-icon-60x60.png')}}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ url('storage/basic/favicons/apple-icon-72x72.png')}}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ url('storage/basic/favicons/apple-icon-76x76.png')}}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ url('storage/basic/favicons/apple-icon-114x114.png')}}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ url('storage/basic/favicons/apple-icon-120x120.png')}}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ url('storage/basic/favicons/apple-icon-144x144.png')}}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ url('storage/basic/favicons/apple-icon-152x152.png')}}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ url('storage/basic/favicons/apple-icon-180x180.png')}}">
<link rel="icon" type="image/png" sizes="192x192"  href="{{ url('storage/basic/favicons/android-icon-192x192.png')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ url('storage/basic/favicons/favicon-32x32.png')}}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ url('storage/basic/favicons/favicon-96x96.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ url('storage/basic/favicons/favicon-16x16.png')}}">
<link rel="manifest" href="{{ url('storage/basic/favicons/manifest.json')}}">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{{ url('storage/basic/favicons/ms-icon-144x144.png')}}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
{!! Theme::asset()->styles() !!}
{!! Theme::asset()->container('bottom-header')->styles() !!}


</head>
<body>
<div id="fb-root"></div>
{!! Theme::partial('header') !!}
<div class="loading"><div class="lds-hourglass"></div></div>
  <div id="app">
    <main role="main" class="main-container container-fluid">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if (session('status'))
        <p class="alert alert-info">{{ session('status') }}</p>
      @endif

      @if (session('success'))
      <div class="alert alert-success mt-2">
        {{ session('success') }}
      </div>
      @endif
      @if (session('error'))
      <div class="alert alert-danger mt-2">
        {{ session('error') }}
      </div>
      @endif

      @if( url()->current() == route('index') || url()->current() == route('gallery'))
        {!! Theme::content() !!}
      @else
        {!! Theme::breadcrumb()->render() !!}
        {!! Theme::content() !!}
          {{-- <aside class="col-md-3 d-none d-sm-block sidebar">
            {!! Theme::partial('right_sidebar') !!}
          </aside> --}}
      @endif
    </main>
  </div>
  {!! Theme::partial('footer') !!}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.15/lodash.core.js"></script>
<script type="text/javascript" src="https://unpkg.com/@popperjs/core@2
"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

@if( url()->current() == route('contact'))
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
@endif

{!! Theme::asset()->scripts() !!}
{!! Theme::asset()->container('footer')->scripts() !!}
</body>
</html>
