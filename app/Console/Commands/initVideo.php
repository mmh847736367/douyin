<?php

namespace App\Console\Commands;

use App\Repositories\KeywordRepository;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Libraries\Http\Resolver;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

class initVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:video';

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
        $page = 1;
        $f = true;

        $client = new Client(['cookies' => true]);
        do {
            $videos = $this->resolver->getHotVideos($client,$page,24);
            var_dump($videos);
            if (empty($videos)) {
                $f = false;
            } else {
                $redisKey = 'meipai.rand.videos.' . $page;

                foreach ($videos as $v) {
                    Log::info('redis cache'.$redisKey,$v);
                    if($f = json_encode($v)) {
                        Redis::lpush($redisKey, json_encode($v));
                    }

                }
                Redis::ltrim($redisKey, 0,30);
                $page++;
            }
            if($page == 20) {
                $f = false;
            }
            sleep(3);
        }while ($f);

    }
}
