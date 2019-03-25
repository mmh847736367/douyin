@extends('frontend.layouts.pc')


@section('content')
<div class="wrapper q">

    <div class="left">

        <div class="item-title mt15"><div class="item-title-txt">热门</div></div>

        <div class="item-video">
            @foreach($viewData['v1'] as $video)
            <div class="video-list item25">
                <div class="videobg">
                    <a href="{{ $video['url'] }}" target="_blank">
                        <div class="videotu">
                            <div class="image image-tu1"><img src="{{ $video['imgUrl'] }}"></div>
                            <p class="video-name">{{ $video['title'] }}​​​​</p>
                            <div class="s-play-pic"></div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="item-title mt15"><div class="item-title-txt">美女</div></div>

        <div class="item-video">
            @foreach($viewData['v2'] as $video)
            <div class="video-list item25">
                <div class="videobg">
                    <a href="{{ $video['url'] }}" target="_blank">
                        <div class="videotu">
                            <div class="image image-tu1"><img src="{{ $video['imgUrl'] }}"></div>
                            <p class="video-name">{{ $video['title'] }}​​​​</p>
                            <div class="s-play-pic"></div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
         <div class="item-title mt15"><div class="item-title-txt">舞蹈</div></div>

        <div class="item-video">
            @foreach($viewData['v3'] as $video)
            <div class="video-list item25">
                <div class="videobg">
                    <a href="{{ $video['url'] }}" target="_blank">
                        <div class="videotu">
                            <div class="image image-tu1"><img src="{{ $video['imgUrl'] }}"></div>
                            <p class="video-name">{{ $video['title'] }}​​​​</p>
                            <div class="s-play-pic"></div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
         <div class="item-title mt15"><div class="item-title-txt">音乐</div></div>

        <div class="item-video">
            @foreach($viewData['v4'] as $video)
            <div class="video-list item25">
                <div class="videobg">
                    <a href="{{ $video['url'] }}" target="_blank">
                        <div class="videotu">
                            <div class="image image-tu1"><img src="{{ $video['imgUrl'] }}"></div>
                            <p class="video-name">{{ $video['title'] }}​​​​</p>
                            <div class="s-play-pic"></div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    @include('frontend.includes.aside')

</div>

@endsection