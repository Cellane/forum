@extends('layouts.app')

@section('content')
  <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="level">
                <span class="flex">
                  <a href="{{ route('profile', $thread->creator) }}">
                    {{ $thread->creator->name }}
                  </a>
                  posted: {{ $thread->title }}
                </span>

                @can('update', $thread)
                  <form action="{{ $thread->path() }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}

                    <button type="submit" class="btn btn-link">Delete Thread</button>
                  </form>
                @endcan
              </div>
            </div>

            <div class="panel-body">
              {{ $thread->body }}
            </div>
          </div>

          <replies :data="{{ $thread->replies }}" @removed="repliesCount--"></replies>

          @if(auth()->check())
            <form action="{{ $thread->path() . '/replies' }}" method="post">
              {{ csrf_field() }}

              <div class="form-group">
                <textarea
                  name="body"
                  id="body"
                  rows="5"
                  class="form-control"
                  placeholder="Have something to say?"
                ></textarea>
              </div>

              <button type="submit" class="btn btn-default">Post</button>
            </form>
          @else
            <p class="text-center">
              Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.
            </p>
          @endif
        </div>

        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-body">
              <p>
                This thread was published
                {{ $thread->created_at->diffForHumans() }}
                by
                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>,
                and currently has
                <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </thread-view>
@endsection