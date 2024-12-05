<?php

namespace App\Console\Commands;

use App\Models\Words;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:words';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate table words with json';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'raw.githubusercontent.com/dwyl/english-words/refs/heads/master/words_dictionary.json';

        $response = Http::get($url);

        if ($response -> successful()){
            $data = $response->json();
            $words=[];

            foreach($data as $word => $value){
                $words[]=['word' => $word];
                
                if(count($words) >= 5000){
                    Words::insertOrIgnore($words);
                    $words=[];
                }
            }

            $this->info('Imported Words Successfully!');
        } else {
            $this->error ('Failed to Import Words from JSON.');
        }
    }
}
