@extends('layouts.app')

@section('head')
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Create a New Thread</div>

          <div class="panel-body">
            <form action="/threads" method="post">
              {{ csrf_field() }}

              <div class="form-group">
                <label for="channel_id">Choose a Channel:</label>
                <select name="channel_id" id="channel_id" class="form-control" required>
                  <option value="">Choose One…</option>

                  @foreach($channels as $channel)
                    <option
                      value="{{ $channel->id }}"
                      {{ old('channel_id') == $channel->id ? 'selected' : '' }}
                    >
                      {{ $channel->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
              </div>

              <div class="form-group">
                <label for="body">Body:</label>
                <wysiwyg name="body"></wysiwyg>
              </div>

              <div class="form-group">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-primary">Publish</button>
              </div>

              @if($errors->any())
                <ul class="alert alert-danger">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              @endif
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
