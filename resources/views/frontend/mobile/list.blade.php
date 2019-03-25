@extends('frontend.layouts.mobile')


@section('title', $viewData['title'].'_'.config('app.name'))

@section('content')
    <div class="box">
        <div class="drawerTitle">{{ $viewData['title'] }}</div>
        <div class="content pm15">
            <ul class="videos">
                @foreach($viewData['videos'] as $video)
                <li class="videos-item">
                    <a href="{{ $video['url'] }}" title="{{ $video['title_complete'] }}">
                        <div class="videos-pic">
                            <div class="videos-tu videos-img"><img src="{{  config('app.mobile_url').$video['imgUrl'] }}" alt="{{ $video['title_complete'] }}"></div>
                        </div>
                        <div class="videos-item-name">​​​{{ $video['title'] }}</div>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        @if(isset($viewData['pageHtml']))
        <div class="page">
            <ul class="page-list">
                {!! $viewData['pageHtml'] !!}
            </ul>
        </div>
        @endif

    </div>
    @include('frontend.includes.maside')
@endsection