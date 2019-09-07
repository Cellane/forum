<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Exception;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store($channel, Thread $thread)
    {
        try {
            $this->validate(request(), [
                'body' => 'required|spamfree'
            ]);

            $reply = $thread->addReply([
                'user_id' => auth()->id(),
                'body' => request('body')
            ]);
        } catch (Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }

        return $reply->load('owner');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validate(request(), [
                'body' => 'required|spamfree'
            ]);
            $reply->update(request(['body']));
        } catch (Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();

        if (request()->expectsJson()) {
            return;
        }

        return back();
    }
}
