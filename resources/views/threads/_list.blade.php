@forelse($threads as $thread)
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="level">
        <div class="flex">
          <h4>
            <a href="{{ $thread->path() }}">
              {{ $thread->title }}

              @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                <span class="badge">Unread</span>
              @endif
            </a>
          </h4>

          <h5>
            Posted by
            <a href="{{ url('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
          </h5>
        </div>

        <a href="{{ $thread->path() }}">
          {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
        </a>
      </div>
    </div>
    <div class="panel-body">
      <article>
        <div class="body">
          {{ $thread->body }}
        </div>
      </article>
    </div>
  </div>
@empty
  <p>There are no relevant results at this time.</p>
@endforelse
