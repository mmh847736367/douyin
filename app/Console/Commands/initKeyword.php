<?php

namespace App\Console\Commands;

use App\Repositories\KeywordRepository;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Libraries\Http\Resolver;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Storage;

class initKeyword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:keyword';

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
        $client = new Client();
        $q = '搞笑';
        $page = 1;
        $f = true;
        $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36';

        do{
            $response = $client->get('https://www.meipai.com/search/topic',[
                'query' => [
                    'q' => $q,
                    'page' => $page
                ],
                'headers' => [
                    'User-Agent' => $ua
                ],
                'verify' => false
            ]);
            $html = $response->getBody()->getContents();
            $crawler = new Crawler($html);
            try{
                $word = $crawler->filter('h4.tcard-name > a')->each(function (Crawler $node,$i) {
                    return ['name' => trim(trim($node->text()),'#')];
                });
                if (empty($word)) {
                    $f = false;
                }else {
                    $redisKey = 'meipai.rand.words.'.$page;

                    foreach($word as $wd) {
                        $word = $this->keywordRepository->create($wd);
                        Redis::lpush($redisKey,$word);

                    }
                }
            }catch (\Exception $e) {
                $f = false;
                echo $e->getMessage();
            }
            $page++;
            sleep(2);

        }while($f);

    }
}
