<?php

namespace App;

class Reputation
{
    const THREAD_PUBLISHED = 10;
    const REPLY_POSTED = 2;
    const BEST_REPLY_AWARDED = 50;

    public static function award($user, $points)
    {
        $user->increment('reputation', $points);
    }
}
