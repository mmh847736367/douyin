
@extends('frontend.layouts.pc')

@section('title', $viewData['title'].'_'.config('app.name'))

@section('content')
<div class="wrapper-play q">


    <div class="play-left mt20">

        <div class="playbox">

            <div class="player">
                <video class="playvideo" controls autoplay="" poster="{{ $viewData['video']['imgUrl'] }}"
                       src="{{ $viewData['video']['videoUrl'] }}"></video>
            </div>

            <div class="topcont">{{ script(url('js/jubao.js')) }}</div>
            @if(!empty($viewData['video']['content']))
            <div class="play_infos"><span class="js">{{ $viewData['video']['content'] }}</div>
            @else
                <div class="play_title">{{ $viewData['video']['title'] }}</div>
            @endif
        </div>


        <div class="item-title mt15">
            <div class="item-title-txt"><p>猜你喜欢</p></div>
        </div>

        <div class="item-video vertical">
            @foreach($viewData['moreVideos'] as $video)
                <div class="video-list item20">
                    <div class="videobg ">
                        <a href="{{ $video['url'] }}" target="_blank">
                            <div class="videotu">
                                <div class="image image-tu1"><img src="{{ $video['imgUrl'] }}" alt="{{ $video['alt'] }}"></div>
                                <p class="video-name">{{ $video['title'] }}</p>
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