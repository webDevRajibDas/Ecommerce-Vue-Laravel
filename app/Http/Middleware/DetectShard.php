<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\ShardManager;
use Illuminate\Http\Request;

class DetectShard
{
    protected $shardManager;

    public function __construct(ShardManager $shardManager)
    {
        $this->shardManager = $shardManager;
    }

    public function handle(Request $request, Closure $next)
    {
        // Detect shard from route parameters or user
        if ($request->route('userId')) {
            $shardKey = $request->route('userId');
        } elseif (auth()->check()) {
            $shardKey = auth()->user()->user_id;
        } else {
            $shardKey = 'default';
        }

        // Store shard info for the request
        $request->attributes->set('shard_key', $shardKey);
        
        return $next($request);
    }
}