<?php

namespace App\Http\Controllers;

use App\Thread;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channel, Thread $thread)
    {
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => request('body')
        ]);

        return redirect($thread->path());
    }
}
