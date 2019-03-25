
@extends('frontend.layouts.mobile')


@section('content')

<div class="box">
    <div class="drawerTitle">热门视频</div>
    <div class="content pm15">
        <ul class="videos">
            @foreach($viewData['v1'] as $v)
                <li class="videos-item"><a href="{{ $v['url'] }}">
                        <div class="videos-pic">
                            <div class="videos-tu videos-img"><img src="{{ config('app.mobile_url').$v['imgUrl'] }}"></div>
                        </div>
                        <div class="videos-item-name">{{ $v['title'] }}</div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="drawerTitle">美女</div>
    <div class="content pm15">
        <ul class="videos">
            @foreach($viewData['v2'] as $v)
                <li class="videos-item"><a href="{{ $v['url'] }}">
                        <div class="videos-pic">
                            <div class="videos-tu videos-img"><img src="{{  config('app.mobile_url').$v['imgUrl'] }}"></div>
                        </div>
                        <div class="videos-item-name">{{ $v['title'] }}</div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="drawerTitle">舞蹈</div>
    <div class="content pm15">
        <ul class="videos">
            @foreach($viewData['v3'] as $v)
                <li class="videos-item"><a href="{{ $v['url'] }}">
                        <div class="videos-pic">
                            <div class="videos-tu videos-img"><img src="{{  config('app.mobile_url').$v['imgUrl'] }}"></div>
                        </div>
                        <div class="videos-item-name">{{ $v['title'] }}</div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="drawerTitle">音乐</div>
    <div class="content pm15">
        <ul class="videos">
            @foreach($viewData['v4'] as $v)
                <li class="videos-item"><a href="{{ $v['url'] }}">
                        <div class="videos-pic">
                            <div class="videos-tu videos-img"><img src="{{  config('app.mobile_url').$v['imgUrl'] }}"></div>
                        </div>
                        <div class="videos-item-name">{{ $v['title'] }}</div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

</div>
@include('frontend.includes.maside')
@endsection