<?php

namespace App\Models;

use App\Models\Concerns\Shardable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Shardable;

    protected $fillable = [
        'order_id', 'user_id', 'amount', 'status'
    ];

    public function getShardKeyName()
    {
        return 'user_id';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')
                    ->onShard($this->user_id);
    }

    public static function createOnShard(array $attributes)
    {
        $order = new static($attributes);
        $order->setShardKey($attributes['user_id']);
        $order->save();
        return $order;
    }

    public static function getByUserId($userId)
    {
        return static::onShard($userId)->where('user_id', $userId)->get();
    }
}