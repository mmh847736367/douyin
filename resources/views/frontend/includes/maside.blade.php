<div class="box">
    <div class="drawerTitle">推荐视频</div>
    <div class="content">
        <ul class="videos">
            @foreach($viewData['relateVideos'] as $video)
            <li class="videos-item"><a href="{{ $video['url'] }}">
                    <div class="videos-pic">
                        <div class="videos-tu videos-img"><img src="{{  config('app.mobile_url').$video['imgUrl'] }}"></div>
                    </div>
                    <div class="videos-item-name">​​​{{ $video['title'] }}</div>
                </a>
            </li>
            @endforeach
        </ul>
    </div>


    <div class="keyword"><span>热门搜索：</span>
        @foreach($viewData['relateWords'] as $word)
        <a href="{{ $word->url }}">{{ $word->name }}</a>
        @endforeach
    </div>
</div>
