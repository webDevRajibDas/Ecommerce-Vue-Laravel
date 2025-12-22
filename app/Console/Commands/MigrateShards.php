<?php

namespace App\Console\Commands;

use App\Services\ShardManager;
use Illuminate\Console\Command;

class MigrateShards extends Command
{
    protected $signature = 'migrate:shards {--fresh} {--seed}';
    protected $description = 'Run migrations on all shards';

    public function handle()
    {
        $shardManager = app(ShardManager::class);
        
        foreach ($shardManager->getAllShards() as $name => $connection) {
            $this->info("Migrating shard: {$name}");
            
            $options = ['--database' => $name];
            
            if ($this->option('fresh')) {
                $this->call('migrate:fresh', $options);
            } else {
                $this->call('migrate', $options);
            }
            
            if ($this->option('seed')) {
                $this->call('db:seed', $options);
            }
        }
        
        $this->info('All shards migrated successfully!');
    }
}