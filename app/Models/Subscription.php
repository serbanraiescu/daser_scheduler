<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['name', 'price', 'duration_days', 'max_uses', 'active'];

    public function clientSubscriptions()
    {
        return $this->hasMany(ClientSubscription::class);
    }
}
