<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTag extends Model
{
    protected $fillable = ['name', 'color'];

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_tag_pivot', 'tag_id', 'client_id');
    }
}
