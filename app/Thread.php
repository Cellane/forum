<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Stevebauman\Purify\Facades\Purify;

class Thread extends Model
{
    use RecordsActivity;
    use Searchable;

    protected $fillable = [
        'slug',
        'user_id',
        'channel_id',
        'best_reply_id',
        'title',
        'body',
        'locked'
    ];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($thread) {
            $thread->update(['slug' => $thread->title]);
            Reputation::award($thread->creator, Reputation::THREAD_PUBLISHED);
        });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
            Reputation::deduct($thread->creator, Reputation::THREAD_PUBLISHED);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function addReply($attributes)
    {
        $reply = $this->replies()->create($attributes);

        event(new ThreadReceivedNewReply($this, $reply));

        return $reply;
    }

    public function markBestReply($reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
        Reputation::award($reply->owner, Reputation::BEST_REPLY_AWARDED);
    }

    public function replyDeleted($reply)
    {
        if ($reply->id === (int)$this->best_reply_id) {
            $this->update(['best_reply_id' => null]);
        }
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where([
                'user_id' => $userId ?: auth()->id()
            ])
            ->delete();
    }

    public function getBodyAttribute($body)
    {
        return Purify::clean($body);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where([
                'user_id' => auth()->id()
            ])
            ->exists();
    }

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = "{$slug}-{$this->id}";
        }

        $this->attributes['slug'] = $slug;
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function searchableAs()
    {
        $env = config('app.env');

        return "{$env}_threads";
    }

    public function toSearchableArray()
    {
        return $this->toArray() + [
            'path' => $this->path()
        ];
    }
}
