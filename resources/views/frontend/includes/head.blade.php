<div class="head2-box">
    <div class="head2 q">
        <div class="top-line"></div>
        <div class="q headtop">

            <div class="logo">
                <a href="{{ config('app.url') }}"><img src="/images/logo.jpg"></a>
            </div>

            <div class="nav">
                <ul>
                    <li><a href="{{ config('app.url') }}">首页</a></li>
                    @foreach($viewData['category'] as $cate)
                    <li class="{{ active_class(Active::checkUriPattern('sq'.$cate['slug'].'*'), 'this') }}"><a href="{{ $cate['url'] }}">{{ $cate['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="search">
                <form name="searchform" method="post" action="/q">
                    @csrf
                    <span>
                        <i class="icon-search"></i>
                        <input name="wd" type="text" id="key" onfocus="this.value=&#39;&#39;;this.style.color=&#39;#000&#39;;" placeholder="输入关键字" class="input_tt">
                    </span>
                    <input type="submit" class="s_btn" value="搜索">
                </form>
            </div>

        </div>
    </div>
</div>
