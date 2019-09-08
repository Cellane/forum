@component('profiles.activities.activity')
  @slot('heading')
    {{ $profileUser->name }}
    <a href="{{ $activity->subject->path() }}">replied</a>
    to
    <a href="{{ $activity->subject->thread->path() }}">{{ $activity->subject->thread->title }}</a>
  @endslot

  @slot('body')
    {!! $activity->subject->body !!}
  @endslot
@endcomponent
