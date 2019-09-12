<?php

namespace App\Http\Controllers;

use App\Thread;

class LockedThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function store(Thread $thread)
    {
        $thread->update(['locked' => true]);
    }

    public function destroy(Thread $thread)
    {
        $thread->update(['locked' => false]);
    }
}
