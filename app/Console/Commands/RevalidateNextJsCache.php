<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RevalidateNextJsCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nextjs:revalidate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revalidate the Next.js cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $response = Http::get(config('app.nextjs_cache_url'), [
            'secret' => config('app.nextjs_cache_secret'),
        ]);

        if ($response->successful()) {
            $this->info("Successfully revalidated cache. Response: " . $response->body());
        } else {
            $this->error("Failed to revalidate cache. Response: " . $response->body());
            dd($response);
        }
    }
}
