@extends('frontend.layouts.mobile')

@section('title', $viewData['title'].'_'.config('app.name'))

@section('content')
<div class="playbox">

    <div class="player">
        <video class="playvideo" controls autoplay="" poster="{{  config('app.mobile_url').$viewData['video']['imgUrl'] }}"
               src="{{ $viewData['video']['videoUrl'] }}"></video>
    </div>

    <div class="topcont">{{ script(url('js/jubao.js')) }}</div>
    @if(!empty($viewData['video']['content']))
        <div class="play_infos"><span class="js">{{ $viewData['video']['content'] }}</div>
    @else
        <div class="play_title">{{ $viewData['video']['title'] }}</div>
    @endif
</div>

<div class="box">
    <div class="drawerTitle">推荐视频</div>
    <div class="content">
        <ul class="videos">
            @foreach($viewData['moreVideos'] as $video)
                <li class="videos-item"><a href="{{ $video['url'] }}">
                        <div class="videos-pic">
                            <div class="videos-tu videos-img"><img alt="{{ $video['alt'] }}" src="{{  config('app.mobile_url').$video['imgUrl'] }}"></div>
                        </div>
                        <div class="videos-item-name">​​​{{ $video['title'] }}</div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@include('frontend.includes.maside')

@endsection
