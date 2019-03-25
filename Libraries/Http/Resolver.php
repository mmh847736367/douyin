<?php

namespace Libraries\Http;


use GuzzleHttp\Client;
use Libraries\Utils\Utils;
use Symfony\Component\DomCrawler\Crawler;

class Resolver
{
    protected $meipaiShowHtml;


    public function requestMeipaiShow($id)
    {
        $url = 'https://www.meipai.com/media/'.$id;
        $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';
        $client = new Client();

        $response = $client->get($url,[
            'headers' => [
                'User-Agent' => $ua
            ],
            'verify' => false
        ]);

        $html = $response->getBody()->getContents();

        $this->meipaiShowHtml = $html;

        return $this;
    }

    public function getMeipaiSearchRes($q)
    {
        $url = 'https://www.meipai.com/search/mv?page=1&fromAll=1&q='.$q;
        $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';
        $client = new Client();

        $response = $client->get($url,[
            'headers' => [
                'User-Agent' => $ua
            ],
            'verify' => false
        ]);

        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);

        $videos = $crawler->filter('ul#mediasList > li')->each(function(Crawler $node, $i) {
            $image = $node->filter('img')->attr('src');
            $imgSlug = Utils::strReplaceEncode(mysub($image, 'com/', '.jpg'));
            try{
                $title_complete = $node->filter('div.pr.cp > a')->attr('title');
            }catch (\InvalidArgumentException $e) {
                $title_complete = '';
            }
            try{
                $title = $node->filter('div.pr.cp > a > strong')->text();
            }catch (\InvalidArgumentException $e) {
                $title = '';
            }
            $realUrl = $node->filter('div.pr.cp > a')->attr('href');
            $slug = Utils::strReplaceEncode(dechex(str_replace('/media/', '',$realUrl)));
            $url = route('frontend.show', $slug, false);
            $imgUrl = route('frontend.image', $imgSlug, false).'.jpg';
            return ['title' => $title, 'url' => $url, 'imgUrl' => $imgUrl,'title_complete' => $title_complete];
        });
        return $videos;
    }

    public function getSecret()
    {
        return mysub($this->meipaiShowHtml,'data-video="', '"');
    }



    public function getMeipaiVideo($id)
    {
        $secret = $this->requestMeipaiShow($id)->getSecret();
        $video['videoUrl'] = Utils::deSecret($secret);
        $crawler = new Crawler($this->meipaiShowHtml);
        $h1_html = $crawler->filter('h1.detail-description')->html();
        $image = $crawler->filter('div#detailVideo > img')->attr('src');
        $imgSlug = Utils::strReplaceEncode(mysub($image, 'com/', '.jpg'));
        $video['imgUrl'] = route('frontend.image', $imgSlug, false).'.jpg';
        $video['content'] = trim(preg_replace("/<(a.*?)>(.*?)<(\/a.*?)>/si","",$h1_html)); //过滤a标签
        $video['content'] = trim(preg_replace("/<(span.*?)>(.*?)<(\/span.*?)>/si","",$video['content'])); //过滤span标签
        $video['content'] = str_replace(['<br>',"\n"],['',''],$video['content']); //过滤\n
        $video['title'] = mb_substr($video['content'], 0,15);
        $video['base64url'] = $secret;
        $video['url'] = route('frontend.show', Utils::strReplaceEncode(dechex($id)),false);
        $video['id'] = $id;
        preg_match_all("/<a.*?>#(.*?)#<\/a.*?>/si",$h1_html,$matches);
        if ($matches) {
            $topic = array_map(function ($word) {
                return ['name' => $word];
            },$matches[1]);
        }
        $relateVideos = $crawler->filter('ul.detail-ul > li > a')->each(function (Crawler $node, $i) {
            $v = [];
            $v['id'] =  str_replace('/media/','',$node->attr('href'));
            $slug = Utils::strReplaceEncode(dechex($v['id']));
            $v['url'] = route('frontend.show', $slug, false);
            $v['title'] = trim($node->filter('p')->text());
            $v['alt'] = $node->filter('img')->attr('alt');
            $v['image'] = $node->filter('img')->attr('src');
            $imgslug = Utils::strReplaceEncode(mysub($v['image'], 'com/', '.jpg'));
            $v['imgUrl'] = route('frontend.image', $imgslug, false).'.jpg';
            return $v;
        });
        return [$video,$relateVideos,$topic];
    }

    public function getRelateWord($q,$limit=10)
    {

        $html = file_get_contents('https://www.meipai.com/search/word_assoc?q='.$q);

        $res = json_decode($html);

        $words = collect($res)->map(function($word) {
            return ['name' => $word];
        })->shuffle()->take($limit);

        return $words;
    }

    public function getRelateWordV2($q)
    {
        if (mb_strlen($q) >= 4) {
            $q1 = mb_substr($q,0,2);
            $q2 = mb_substr($q,-2);
            $words1 = $this->getRelateWord($q1,5);
            $words2 = $this->getRelateWord($q2,5);
            $words = $words1->merge($words2);
        }else{
            $words = $this->getRelateWord($q);
        }
        return $words;
    }

    public function getCategoryVideos($tid,$page,$count = 24)
    {
        if($tid == 0) {
            $url = 'https://www.meipai.com/home/hot_timeline?page='.$page.'&count=12';
        }else{
            $url = 'https://www.meipai.com/squares/new_timeline?page='.$page.'&count='.$count.'&tid='.$tid;
        }
        $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';
        $client = new Client();

        $response = $client->get($url,[
            'headers' => [
                'User-Agent' => $ua,
                'Referer' => 'https://www.meipai.com/square/'.$tid
            ],
            'verify' => false
        ]);
        $html = $response->getBody()->getContents();
        $res = json_decode($html,true);
        $videos = [];
        foreach ($res['medias'] as $v) {
            $video = [];
            $video['id'] = $v['id'];
            $video['title_complete'] = str_replace(['<br>',"\n"],['',''],$v['caption_complete']);
            $video['title'] = str_replace(['<br>',"\n"],['',''],$v['caption']);
            $video['url'] = route('frontend.show',Utils::strReplaceEncode(dechex($v['id'])), false);
            $video['image'] = $v['cover_pic'];
            $imgSlug = Utils::strReplaceEncode(mysub($v['cover_pic'], 'com/', '.jpg'));
            $video['imgUrl'] = route('frontend.image', $imgSlug, false).'.jpg';
            $videos[] = $video;
        }
        return $videos;
    }

    public function getHotVideos(Client $client,$page,$count=12)
    {
        $url = 'https://www.meipai.com/home/hot_timeline?page='.$page.'&count='.$count;
        $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';
        $response = $client->get($url,[
            'headers' => [
                'User-Agent' => $ua,
            ],
            'verify' => false
        ]);
        $html = $response->getBody()->getContents();
        $res = json_decode($html,true);
        $videos = [];
        foreach ($res['medias'] as $v) {
            $video = [];
            $video['id'] = $v['id'];
            $video['title_complete'] = str_replace(['<br>',"\n"],['',''],$v['caption_complete']);
            $video['title'] = str_replace(['<br>',"\n"],['',''],$v['caption']);
            $video['url'] = route('frontend.show',Utils::strReplaceEncode(dechex($v['id'])), false);
            $video['image'] = $v['cover_pic'];
            $imgSlug = Utils::strReplaceEncode(mysub($v['cover_pic'], 'com/', '.jpg'));
            $video['imgUrl'] = route('frontend.image', $imgSlug, false).'.jpg';
            $videos[] = $video;
        }
        return $videos;
    }
}

