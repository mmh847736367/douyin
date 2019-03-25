<div class="right">

    <div class="item-title mt15">
        <div class="item-title-txt">推荐视频</div>
    </div>

    @foreach($viewData['relateVideos'] as $video)
    <div class="rightitem">
        <a href="{{ $video['url'] }}" target="_blank">
            <div class="rightitem-text">{{ $video['title'] }}</div>
            <div class="rightitem-tu">
                <div class="image image-tu1"><img src="{{ $video['imgUrl'] }}"></div>
            </div>
        </a>
    </div>
    @endforeach

    <div class="item-title mt15">
        <div class="item-title-txt">热门搜索</div>
    </div>
    <div class="keyword">
        <ul>
            @foreach($viewData['relateWords'] as $word)
            <li><a href="{{ $word->url }}">{{ $word->name }}</a></li>
            @endforeach
        </ul>
    </div>

</div>

