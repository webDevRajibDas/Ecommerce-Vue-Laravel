<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class ShardManager
{
    protected $shards;
    protected $strategy;
    protected $ranges;
    protected $directory;

    public function __construct()
    {
        $shardingConfig = config('database.sharding', []);
        
        $this->shards = Arr::get($shardingConfig, 'shards', []);
        $this->strategy = Arr::get($shardingConfig, 'strategy', 'hash');
        $this->ranges = Arr::get($shardingConfig, 'ranges', []);
        $this->directory = Arr::get($shardingConfig, 'directory', []);
    }

    /**
     * Get shard name based on shard key and strategy
     */
    public function getShardName($shardKey)
    {
        return match($this->strategy) {
            'range' => $this->getShardByRange($shardKey),
            'directory' => $this->getShardByDirectory($shardKey),
            default => $this->getShardByHash($shardKey),
        };
    }

    /**
     * Hash-based sharding (default)
     */
    protected function getShardByHash($shardKey)
    {
        $hash = crc32($shardKey);
        $shardIndex = abs($hash) % count($this->shards);
        $shardNames = array_keys($this->shards);
        
        return $shardNames[$shardIndex];
    }

    /**
     * Range-based sharding
     */
    protected function getShardByRange($shardKey)
    {
        // If shard key is numeric, use numeric ranges
        if (is_numeric($shardKey)) {
            $value = (int) $shardKey;
            foreach ($this->ranges as $shardName => $range) {
                if ($value >= $range['min'] && $value <= $range['max']) {
                    return $shardName;
                }
            }
        }
        
        // Fallback to hash-based for non-numeric keys
        return $this->getShardByHash($shardKey);
    }

    /**
     * Directory-based sharding
     */
    protected function getShardByDirectory($shardKey)
    {
        // Check if there's a specific mapping
        if (isset($this->directory[$shardKey])) {
            return $this->directory[$shardKey];
        }
        
        // Fallback to hash-based
        return $this->getShardByHash($shardKey);
    }

    /**
     * Get database connection for a shard
     */
    public function getShardConnection($shardKey)
    {
        $shardName = $this->getShardName($shardKey);
        $this->registerShardConnection($shardName);
        return $shardName;
    }

    /**
     * Register a shard connection dynamically
     */
    public function registerShardConnection($shardName)
    {
        if (!config("database.connections.{$shardName}") && isset($this->shards[$shardName])) {
            config(["database.connections.{$shardName}" => $this->shards[$shardName]]);
        }
    }

    /**
     * Get all shard connections
     */
    public function getAllShards()
    {
        $shards = [];
        
        foreach (array_keys($this->shards) as $shardName) {
            $this->registerShardConnection($shardName);
            $shards[$shardName] = DB::connection($shardName);
        }
        
        return $shards;
    }

    /**
     * Get specific shard connection by name
     */
    public function getShardByName($shardName)
    {
        if (!isset($this->shards[$shardName])) {
            throw new \InvalidArgumentException("Shard {$shardName} does not exist");
        }
        
        $this->registerShardConnection($shardName);
        return DB::connection($shardName);
    }

    /**
     * Execute callback on all shards
     */
    public function executeOnAllShards(callable $callback)
    {
        $results = [];
        
        foreach ($this->getAllShards() as $shardName => $connection) {
            $results[$shardName] = $callback($connection, $shardName);
        }
        
        return $results;
    }

    /**
     * Get shard statistics
     */
    public function getShardStats()
    {
        return $this->executeOnAllShards(function ($connection, $shardName) {
            try {
                $userCount = $connection->table('users')->count();
                $orderCount = $connection->table('orders')->count();
                
                return [
                    'users' => $userCount,
                    'orders' => $orderCount,
                    'status' => 'connected',
                ];
            } catch (\Exception $e) {
                Log::error("Failed to get stats for shard {$shardName}: " . $e->getMessage());
                
                return [
                    'users' => 0,
                    'orders' => 0,
                    'status' => 'error',
                    'error' => $e->getMessage(),
                ];
            }
        });
    }

    /**
     * Get shard configuration
     */
    public function getShardConfig($shardName = null)
    {
        if ($shardName) {
            return $this->shards[$shardName] ?? null;
        }
        
        return $this->shards;
    }

    /**
     * Add a new shard dynamically
     */
    public function addShard($shardName, array $config)
    {
        $this->shards[$shardName] = $config;
        config(["database.sharding.shards.{$shardName}" => $config]);
        
        return $this;
    }

    /**
     * Get total number of shards
     */
    public function getTotalShards()
    {
        return count($this->shards);
    }
}