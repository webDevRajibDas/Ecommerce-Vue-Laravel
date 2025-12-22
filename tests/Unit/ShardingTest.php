<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\ShardManager;
use Tests\TestCase;

class ShardingTest extends TestCase
{
    public function test_shard_distribution()
    {
        $shardManager = app(ShardManager::class);
        
        $testUsers = [
            'user_001' => 'shard1',
            'user_002' => 'shard2', 
            'user_003' => 'shard3',
        ];
        
        foreach ($testUsers as $userId => $expectedShard) {
            $actualShard = $shardManager->getShardName($userId);
            $this->assertEquals($expectedShard, $actualShard);
        }
    }
    
    public function test_user_creation_on_shard()
    {
        $userData = [
            'user_id' => 'test_user_123',
            'email' => 'test@example.com',
            'name' => 'Test User',
        ];
        
        $user = User::createOnShard($userData);
        
        $this->assertNotNull($user->id);
        $this->assertEquals($userData['user_id'], $user->user_id);
    }
}