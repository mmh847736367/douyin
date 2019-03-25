
@extends('frontend.layouts.pc')

@section('title', $viewData['title'].'_'.config('app.name'))

@section('content')
<div class="wrapper q">


    <div class="left">

        <div class="item-title mt15">
            <div class="item-title-txt"><span>{{ $viewData['title'] }}</span></div>
        </div>

        <div class="item-video">
            @foreach($viewData['videos'] as $video)
            <div class="video-list item25">
                <div class="videobg">
                    <a href="{{ $video['url'] }}" target="_blank" title="{{ $video['title_complete'] }}">
                        <div class="videotu">
                            <div class="image image-tu1"><img src="{{ $video['imgUrl'] }}" alt="{{ $video['title_complete'] }}"></div>
                            <p class="video-name">{{ $video['title'] }}​​​​</p>
                            <div class="s-play-pic"></div>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach

        @if(isset($viewData['pageHtml']))
        <div class="box q">
            <div class="page">
                <div class="pagelist">
                    {!! $viewData['pageHtml'] !!}
                </div>
            </div>
        </div>
        @endif
    </div>
    </div>


    @include('frontend.includes.aside')

</div>
@endsection
