<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\KeywordRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Libraries\Http\Resolver;
use Libraries\Utils\Page;
use Libraries\Utils\Utils;

/**
 * Class HomeController.
 */
class HomeController extends Controller
{

    protected $cate = [
        'gaoxiao' => ['name' => '搞笑','tid' => 13, 'slug' => 'gaoxiao'],
        'meinv' => ['name' => '美女','tid' => 474, 'slug' => 'meinv'],
        'wudao' => ['name' => '舞蹈','tid' => 63, 'slug' => 'wudao'],
        'yinyue' => ['name' => '音乐','tid' => 62, 'slug' => 'yinyue'],
        'meizhuang' => ['name' => '美妆','tid' => 27, 'slug' => 'meizhuang'],
        'mengchong' => ['name' => '萌宠', 'tid' => 6, 'slug' => 'mengchong']
    ];

    protected $viewData;
    protected $resolver;

    protected $keywordRespository;

    public function __construct(Resolver $resolver, KeywordRepository $keywordRepository)
    {
        $this->resolver = $resolver;
        $this->keywordRespository = $keywordRepository;
        $this->viewData['category'] = collect($this->cate)->map(function ($cate) {
            $cate['url'] = route('frontend.category',$cate['slug']).'/';
            return $cate;
        });
        $this->viewData['relateWords'] = $this->_initRelateWords();
        $this->viewData['relateVideos'] = $this->_initRelateVideos();
    }

    public function _initRelateVideos()
    {
       $videos =  Redis::lrange('meipai.rand.videos.'.rand(1,20),0,29);
       foreach ($videos as $k => $video) {
           $videos[$k] = json_decode($video, true);
       }
       shuffle($videos);
       return array_slice($videos,0,6);
    }

    public function _initRelateWords()
    {
        $words =  Redis::lrange('meipai.rand.words.'.rand(1,20),0,29);
        $words = collect($words)->map(function($word) {
            $word = \GuzzleHttp\json_decode($word);
            return $this->keywordRespository->where('name', $word->name)->get()->first();
        })->shuffle()->take(8);
        return $words;
    }

    public function index()
    {
        $this->viewData['v1'] = $this->_getVideosByTid(0);
        $this->viewData['v2'] = $this->_getVideosByTid(474);
        $this->viewData['v3'] = $this->_getVideosByTid(63);
        $this->viewData['v4'] = $this->_getVideosByTid(62);

        return view(isMobile() ? 'frontend.mobile.index' : 'frontend.index',['viewData' => $this->viewData]);
    }

    public function _getVideosByTid($tid)
    {
        $videos =  Redis::lrange('meipai.index.tid.'.$tid,0,7);
        foreach ($videos as $k => $video) {
            $videos[$k] = json_decode($video, true);
        }
        return $videos;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $id = hexdec(Utils::strReplaceDecode($slug));
        $redisKey = 'video'.$id;
        if (Cache::has($redisKey)) {
            $this->viewData = Cache::get($redisKey);
        } else {
            $this->viewData['id'] = $id;
            list($video,$moreVideo,$topic) = $this->resolver->getMeipaiVideo($id);
            $this->viewData['video'] = $video;
            $this->viewData['title'] = $video['title'];
            $this->viewData['moreVideos'] = $moreVideo;
            if(!empty($topic)) {
                $topic = $this->keywordRespository->createMultiple($topic);

                $this->viewData['relateWords'] = $topic->merge($this->viewData['relateWords']);
            }
            if (json_encode($video)) {
                Cache::put($redisKey,$this->viewData,3*60*24);
            }
        }
        if($f = json_encode($this->viewData['video'])) {
            $key = 'meipai.rand.videos.'.rand(1,20);
            Redis::lpush($key, $f);
            Redis::ltrim($key,0,29);
        }
        return view(isMobile() ? 'frontend.mobile.show' : 'frontend.show',['viewData' => $this->viewData]);
    }

    public function search($slug)
    {
        $q = $this->keywordRespository->getBySlug($slug);
        $this->viewData['title'] = $q->name;
        $redisKey = 'meipai.rand.words.'.rand(1,20);
        Redis::lpush($redisKey,$q);
        Redis::ltrim($redisKey,0,29);
        $videos = $this->resolver->getMeipaiSearchRes($q->name);
        $this->viewData['videos'] = $videos;
        $relateWords = $this->resolver->getRelateWordV2($q->name);
        $this->viewData['relateWords'] = $this->keywordRespository->createMultiple($relateWords->all());
        return view(isMobile() ? 'frontend.mobile.list' : 'frontend.list', ['viewData' => $this->viewData]);
    }

    public function userSearch(Request $request)
    {

        $message = [
            'required' => '搜索内容不能为空！',
            'max' => '搜索内容过长！'
        ];
        $validator = \Validator::make($request->all(),[
            'wd' => 'required|max:10'
        ],$message);

        if($validator->fails()) {
            return redirect()->route('frontend.index');
        }
        
        $wd = $request->input('wd');

        $word = $this->keywordRespository->create(['name' => $wd]);

        return redirect()->route('frontend.search',$word->slug);
    }

    public function category($slug, $page = '1.html')
    {
        $page = (int) $page ?: 1;
        if(array_key_exists($slug,$this->cate)) {
            $tid = $this->cate[$slug]['tid'];
            $category = $this->cate[$slug]['name'];
            $uri = '/sq'.$this->cate[$slug]['slug'];
        }else {
            abort(404);
        }
        $this->viewData['title'] = $category;
        $relateWords = json_decode(Storage::get($category.'.json'), true);
        $relateWords = collect($relateWords)->shuffle()->take(10)->map(function ($word) {
            return ['name' => $word];
        });
        $this->viewData['relateWords'] = $this->keywordRespository->createMultiple($relateWords->all());

        $page = (int) $page ?: 1;
        $this->viewData['pageHtml'] = (new Page($uri,$page,10))->init();
        $this->viewData['videos'] = $this->resolver->getCategoryVideos($tid,$page);
        return view(isMobile() ? 'frontend.mobile.list' : 'frontend.list', ['viewData' => $this->viewData]);
    }
}
