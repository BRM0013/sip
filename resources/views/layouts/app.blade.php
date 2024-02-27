<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-HC3T8E589Y"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-HC3T8E589Y');
</script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ url('/') }}/bower_components/select2/dist/css/select2.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/') }}/build/sweetalert/sweetalert.css">
  	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {font-family: Arial;}

        /* Style the tab */
        .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
        }

        /* Style the buttons inside the tab */
        .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
        background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
        background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
        background: white;
        }
        .navbar-collapse .in{
            display: block;
            height: 169px;
        }
    </style>
</head>
<body style="background-image: url({{asset('img/bg_login/bg.png')}});">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light  shadow-sm" style="background-color: #189be7; opacity: 0.8;">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}" style="color: white; font-family: Gabriola; font-size: 30px;">
                    <!-- {{ config('app.name', 'Laravel') }} -->
                    <b>SDK</b> Dinas Kesehatan Sidoarjo
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
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
                            <li class="nav-item" style="background: #4289E3">
                                <a class="nav-link btn btn-outline-info waves-effect" href="{{ route('login') }}" style="color: white; font-weight: bold;">{{ __('Login') }}</a>
                            </li>
                            &nbsp;
                                <li class="nav-item" style="background-color: #284555">
                                    <a class="nav-link btn btn-outline-info waves-effect" href="{{ route('cek_sip') }}" style="color: white;font-weight: bold;">{{ __('Pengecekan Surat Izin Praktik') }}</a>
                                </li>
                            <!-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link btn btn-outline-info waves-effect" href="{{ route('register') }}" style="color: white;">{{ __('Registrasi') }}</a>
                                </li>
                            @endif -->
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
                        &nbsp;
                        <li class="nav-item"style="background-color: crimson ">
                            <a class="nav-link btn btn-outline-info waves-effect" href="{{ route('manual_guide') }}" style="color: white; font-weight: bold;">Buku Panduan</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="{{ url('/') }}/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="{{ url('/') }}/build/sweetalert/sweetalert.min.js"></script>
    <!-- Select2 -->
    <script src="{{ url('/') }}/bower_components/select2/dist/js/select2.full.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2();
        });
        @if(!empty(Session::get('message')))
            swal({
                title : "{{ Session::get('title') }}",
                text : "{{ Session::get('message') }}",
                type : "{{ Session::get('type') }}",
                showConfirmButton: true
            });
        @endif
    </script>
@yield('js')
</body>
</html>
