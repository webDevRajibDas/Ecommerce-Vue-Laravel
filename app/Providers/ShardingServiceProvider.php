<?php

namespace App\Providers;

use App\Services\ShardManager;
use Illuminate\Support\ServiceProvider;

class ShardingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ShardManager::class, function ($app) {
            return new ShardManager();
        });
    }

    public function boot()
    {
        // Register shard connections
        $shardManager = app(ShardManager::class);
        
        foreach (config('database.sharding.shards', []) as $name => $config) {
            config(["database.connections.{$name}" => $config]);
        }
    }
}