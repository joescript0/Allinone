<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="theme-color" content="#fcb800">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>@yield('title') - @yield('name') </title>
    @yield('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @include('files.css')
</head>

<body style="background:rgb(255, 255, 255);" data-ma-theme="blue">
    <main class="main">
        @yield('body')
    </main>
    @include('files.js')
    @yield('js-code')
    <!-- <div style="background-color: rgb(0, 0, 0);padding-top: 20px;padding-bottom: 20px;border-top: 4px solid rgb(251, 187, 27);text-align: center;color: white;" id="footer">Folderit © 2024</div> -->
</body>

</html>
