<?php

namespace App\Models\Concerns;

use App\Services\ShardManager;

trait Shardable
{
    protected static $shardManager;
    protected $shardKey;

    public static function bootShardable()
    {
        static::$shardManager = app(ShardManager::class);
    }

    public function setShardKey($key)
    {
        $this->shardKey = $key;
        return $this;
    }

    public function getShardKey()
    {
        return $this->shardKey ?? $this->getAttribute($this->getShardKeyName());
    }

    public function getShardKeyName()
    {
        return 'user_id'; // Default shard key
    }

    public function getConnectionName()
    {
        if ($this->shardKey) {
            return static::$shardManager->getShardConnection($this->shardKey);
        }

        return parent::getConnectionName();
    }

    // Query scopes for sharding
    public function scopeOnShard($query, $shardKey)
    {
        $connection = static::$shardManager->connectToShard($shardKey);
        $model = new static;
        $model->setConnection($connection->getName());
        $model->setShardKey($shardKey);
        
        return $model->newQuery();
    }
}