<div class="mb-top"><a class="main-icon" href="javascript:history.back(-1)"></a>
    <div class="search">
        <form name="searchform" method="post" action="/q"><input type="submit" class="icon-sousuo" value="">
            @csrf
            <span>
                <input name="wd" type="text" id="key" onfocus="this.value=&#39;&#39;;this.style.color=&#39;#000&#39;;" class="input_tt" placeholder="搜索视频">
            </span>
        </form>
    </div>
</div>
<div class="navehih">
    <div class="nave">
        <ul class="navelist">
            <li><a class="{{ request()->is('/') ? 'this' : ''}}" href="/">首页</a></li>
            @foreach($viewData['category'] as $cate)
            <li><a class="{{ active_class(Active::checkUriPattern('sq'.$cate['slug'].'*'), 'this') }}" href="{{ $cate['url'] }}">{{ $cate['name'] }}</a></li>
            @endforeach
        </ul>
    </div>
    <div class="list_shadow"></div>
</div>
