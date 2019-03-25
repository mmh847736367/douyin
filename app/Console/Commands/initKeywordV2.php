<?php

namespace App\Console\Commands;

use App\Repositories\KeywordRepository;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Libraries\Http\Resolver;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Storage;

class initKeywordV2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:keyword2';

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
        $wd1 = Storage::get('搞笑.json');
        $wd2 = Storage::get('游戏.json');
        $wd3 = Storage::get('美女.json');
        $wd4 = Storage::get('舞蹈.json');
        $wd5 = Storage::get('美妆.json');


        $words = collect(json_decode($wd1))
            ->merge(collect(json_decode($wd2)))
            ->merge(collect(json_decode($wd3)))
            ->merge(collect(json_decode($wd4)))
            ->merge(collect(json_decode($wd5)));

        $words = $words->shuffle()->take(600);
        $i = 1;
        $j = 1;

        foreach ($words as $word) {
            $redisKey = 'meipai.rand.words.'.$j;
            $word = $this->keywordRepository->create(['name' => $word]);
            Redis::lpush($redisKey,$word);
            $i++;
            if($i > 30) {
                $i = 1;
                $j++;
            }
            if($j == 21) {
                break;
            }
        }
    }
}
