<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="keywords" content="{!! Theme::get('keywords') !!}">
    <meta name="description" content="{!! Theme::get('description') !!}">
    <meta name="copyright" content="NhaTrangNet" />

    <meta name="author" content="NhaTrangNet" />
    @if(strpos(url()->current(), 'admin') !== false)
    <meta name="robots" content="noindex,nofollow" />
    @else
    <meta name="robots" content="index,follow" />
    @endif

    <meta http-equiv="content-language" content="vi"/>
    <meta name="geo.region" content="VN" />
    <meta name="geo.placename" content="Nha Trang" />
    <meta name="geo.position" content="12.238791;109.196749" />
    <meta name="ICBM" content="12.238791, 109.196749" />

    <meta name="revisit-after" content=" days">
    <meta name="DC.title"  content=""/>
    <meta name="dc.description" content="">
    <meta name="dc.keywords" content="">
    <meta name="dc.subject" content="?=stripslashes($result['commonContent']['setting'][73]->value)?>">
    <meta name="dc.created" content="2018-08-03">
    <meta name="dc.publisher" content="NhaTrangNet">
    <meta name="dc.rights.copyright" content="NhaTrangNet">
    <meta name="dc.creator.name" content="NhaTrangNet">
    <meta name="dc.creator.email" content="cnttnt@gmail.com">
    <meta name="dc.identifier" content="">
    <meta name="dc.language" content="vi-VN">


    <title>{{ config('app.name', 'NhaTrangNet') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin_login.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @if( url()->current() != route('admin.login') && url()->current() != route('admin.register.submit'))
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    @endif
    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
{{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
</html>