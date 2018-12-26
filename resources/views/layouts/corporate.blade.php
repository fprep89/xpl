<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <title>@yield('title')</title>
    <!-- Styles -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    
    @if(isset($editor))
    <link href="{{asset('js/summernote/summernote-bs4.css')}}" rel="stylesheet">
    @endif
    <link rel="shortcut icon" href="{{asset('/favicon.ico')}}" />
    </head>
    <body>
    <div id="app" >
    @yield('content-main')
    </div>
 
    <div class="nav-bg-dark">
    <footer class=" wrapper  text-light footer">
        <div class="container py-3">
            @include('snippets.bottommenu-corporate')
        </div>
    </footer>
    </div>

    <!-- Scripts -->
     @include('snippets.scripts')
    </body>
    </html>
