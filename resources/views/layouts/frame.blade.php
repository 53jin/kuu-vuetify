<!DOCTYPE html>
<html lang="en" style="background-color: rgb(238, 245, 249);">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <style type="text/css">
        .preloader {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            right: 0;
            z-index: 2001;
            background-color: rgba(0,0,0,.2);
            -webkit-transition: opacity .3s ease-in-out;
            -moz-transition: opacity .3s ease-in-out;
            -o-transition: opacity .3s ease-in-out;
            transition: opacity .3s ease-in-out;
        }
        .preloader .loading-img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
        }
        .preloader .loading-img img {
            opacity: .85;
            width: 6rem;
            height: 6rem;
        }
    </style>
    @stack('styles')
</head>
<body class="@yield('body.class')" style="@yield('body.style')">
<div class="preloader"><div class="loading-img"><img src="/img/rubik-loading.gif" alt="loading..."/></div></div>
<div id="app-wrapper">@yield('body')</div>
@stack('scripts')
</body>
</html>
