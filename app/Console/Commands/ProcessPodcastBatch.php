<?php

namespace App\Console\Commands;

use App\Jobs\ProcessPodcast;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Throwable;

class ProcessPodcastBatch extends Command
{
    protected $signature = 'podcast:process {count=20}';
    protected $description = 'Run a batch of podcast processing jobs';

    public function handle()
    {
        $count = (int) $this->argument('count');

        $jobs = [];
        for ($i = 1; $i <= $count; $i++) {
            $jobs[] = new ProcessPodcast($i);
        }

        $batch = Bus::batch($jobs)
            ->name('ProcessPodcastBatch')
            ->then(function (Batch $batch) {
                logger()->info("Batch Finished: {$batch->id}");
            })
            ->catch(function (Batch $batch, Throwable $e) {
                logger()->error("Batch Failed: {$batch->id} Error: {$e->getMessage()}");
            })
            ->finally(function (Batch $batch) {
                logger()->info("Batch Ended: {$batch->id}");
            })
            ->dispatch();

        $this->info("Batch Created: {$batch->id}");
    }
}
