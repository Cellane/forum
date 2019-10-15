<?php

namespace App;

trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            if ($this->owner) {
                Reputation::award($this->owner, Reputation::REPLY_FAVORITED);
            }

            return $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        $this->favorites()
            ->where($attributes)
            ->get()
            ->each
            ->delete();

        if ($this->owner) {
            Reputation::deduct($this->owner, Reputation::REPLY_FAVORITED);
        }
    }

    public function isFavorited()
    {
        return !!$this->favorites
            ->where('user_id', auth()->id())
            ->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
