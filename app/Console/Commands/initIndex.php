<?php

namespace App\Console\Commands;

use App\Repositories\KeywordRepository;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Libraries\Http\Resolver;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

class initIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    protected $keywordRepository;

    protected $resolver;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(KeywordRepository $keywordRepository, Resolver $resolver)
    {
        $this->keywordRepository = $keywordRepository;
        $this->resolver = $resolver;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * 0 热门 474 高颜值 63 舞蹈 62 音乐 13 搞笑
         */
        $tids = ['0','474','63','62'];
        $client = new Client();
        foreach ($tids as $tid) {
            $page = 1;
                $videos = $this->resolver->getCategoryVideos($tid, $page, 8);
                var_dump($videos);
                if (empty($videos)) {
                    $f = false;
                } else {
                    $redisKey = 'meipai.index.tid.'.$tid;
                    var_dump($videos);
                    foreach ($videos as $v) {
                        $client->get($v['url']);
                        sleep(1);
                        if($f = json_encode($v)) {
                            Redis::lpush($redisKey, json_encode($v));
                        }
                    }
                    Redis::ltrim($redisKey, 0,10);
                }

                sleep(3);
        }
    }
}
