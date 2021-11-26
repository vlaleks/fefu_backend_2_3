<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use App\Models\Redirect;
use Illuminate\Support\Facades\DB;

class ChangeNewsSlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change_news_slug {oldSlug} {newSlug}';

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
     * @return int
     */
    public function handle()
    {
        $oldSlug = $this->argument('oldSlug');
        $newSlug = $this->argument('newSlug');

        if ($oldSlug === $newSlug) {
            $this->error('Не должны совпадать');
            
            return 1;
        }

        $from = route('news_item', ['slug' => $oldSlug], false);
        $to = route('news_item', ['slug' => $newSlug], false);
        $redirect = Redirect::query()
        ->where('old_slug', $from)
        ->where('new_slug', $to)
        ->first();
        if ($redirect !== null) {
            $this->error('Уже существует');
            
            return 1;
        }

        $news = News::where('slug', $oldSlug)->first();
        if ($news === null) {
            $this->error('Не найдено');
            
            return 1;
        }

        DB::transaction(function () use ($news, $newSlug, $to) {
            Redirect::where('old_slug', $to)->delete();

            $news->slug = $newSlug;
            $news->save();
        });

        return 0;
    }
}
