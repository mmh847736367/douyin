<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="applicable-device" content="pc">
    <meta http-equiv="Cache-Control" content="no-transform">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="mobile-agent" content="format=html5; url={{ config('app.mobile_url').$_SERVER['REQUEST_URI'] }}">
    <meta name="mobile-agent" content="format=xhtml; url={{ config('app.mobile_url').$_SERVER['REQUEST_URI'] }}">
    <meta name="referrer" content="never">
    <link rel="shortcut icon" href="favicon.ico" type="images/x-icon">
    <meta name="viewport" content="maximum-scale=1">
    <title>@yield('title', config('app.name'))</title>
    {{ style(url('css/wwww.css')) }}
    @if(request()->is('/'))
    <meta name="keywords" content="{{ config('app.kwd') }}">
    <meta name="description" content="{{ config('app.desc') }}">
    @endif
    <script>
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            window.location = window.location.href.replace("www.jiujiudaquan.com","m.jiujiudaquan.com");
        }
    </script>
</head>
<body>

@include('frontend.includes.head')

@yield('content')

<div class="footer">
    <p>
        Copyright &copy; {{ date('Y') }} <a href="{{ config('app.url') }}">{{ config('app.name') }}</a> , All Rights Reserved <a href="#" target="_blank" title="站长统计">站长统计</a>
    </p>
</div>


</body>
</html>
