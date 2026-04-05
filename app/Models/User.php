<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\ShardManager;
use App\Models\Concerns\Shardable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable , Shardable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_id', 
        'shard_key',
        'is_admin',
    ];

    
    public function getShardKeyName()
    {
        return 'user_id';
    }

    // Relationship that respects sharding
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id')
                    ->onShard($this->user_id);
    }


    // Create user on correct shard
    public static function createOnShard(array $attributes)
    {
        $user = new static($attributes);
        $user->setShardKey($attributes['user_id']);
        $user->save();
        return $user;
    }

// Find user across all shards
    public static function findAcrossShards($userId)
    {
        $shardManager = app(ShardManager::class);
        
        foreach ($shardManager->getAllShards() as $shard) {
            $user = $shard->table('users')
                         ->where('user_id', $userId)
                         ->first();
            
            if ($user) {
                return (new static)->newFromBuilder($user)
                                 ->setShardKey($userId);
            }
        }
        
        return null;
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }
}
