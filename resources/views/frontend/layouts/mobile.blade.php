<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta content="telephone=no" name="format-detection">
    <meta name="full-screen" content="yes">
    <meta name="x5-fullscreen" content="true">
    <meta name="applicable-device" content="mobile">
    <meta name="referrer" content="never">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" href="/favicon.ico" type="images/x-icon">
    {{ style(url('css/mobilecss.css')) }}
    @if(request()->is('/'))
    <meta name="keywords" content="{{ config('app.kwd') }}">
    <meta name="description" content="{{ config('app.desc') }}">
    @endif
    <script>
        if(! /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            window.location = window.location.href.replace("{{ config('app.asset_url') }}","{{ config('app.asset_mobile_url') }}");
        }
    </script>
</head>
<body>
@include('frontend.includes.mobileNav')

@yield('content')

<div id="foot">
    <div class="foot-copyright">&copy;{{ date('Y') }} <a href="{{ config('app.mobile_url') }}">{{ config('app.name') }}</a> All Rights Reserved</div>
</div>


</body>
</html>
