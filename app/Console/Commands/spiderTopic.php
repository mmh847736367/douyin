<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Storage;

class spiderTopic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:topic {q}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $q = $this->argument('q');
        $page = 1;
        $f = true;
        $words = collect();
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
                    return trim(trim($node->text()),'#');
                });
                $page++;
                if (empty($word)) {
                    $f = false;
                }else {
                    var_dump($word);
                    $words = $words->merge($word);
                }
            }catch (\Exception $e) {
                $f = false;
            }

            sleep(2);
        }while($f);

        Storage::put($q.'.json',$words->toJson());
    }
}
