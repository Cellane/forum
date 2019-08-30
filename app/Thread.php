<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function addReply($attributes)
    {
        $this->replies()->create($attributes);
    }

    public function path()
    {
        return '/threads/' . $this->id;
    }
}
