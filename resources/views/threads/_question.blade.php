<div class="panel panel-default" v-if="editing">
<div class="panel-heading">
    <div class="level">
      <input type="text" class="form-control" v-model="form.title">
    </div>
  </div>

  <div class="panel-body">
    <div class="form-group">
      <textarea class="form-control" rows="10" v-model="form.body"></textarea>
    </div>
  </div>

  <div class="panel-footer">
    <div class="level">
      <button class="btn btn-xs btn-primary mr-1" @click="update">Update</button>
      <button class="btn btn-xs" @click="cancel">Cancel</button>

      @can('update', $thread)
        <form action="{{ $thread->path() }}" method="post" class="ml-a">
          {{ csrf_field() }}
          {{ method_field('delete') }}

          <button type="submit" class="btn btn-xs btn-danger">Destroy</button>
        </form>
      @endcan
    </div>
  </div>
</div>

<div class="panel panel-default" v-else>
  <div class="panel-heading">
    <div class="level">
      <img
        src="{{ $thread->creator->avatar_path }}"
        alt="{{ $thread->creator->name }}"
        width="25"
        height="25"
        class="mr-1"
      >

      <span class="flex">
        <a href="{{ route('profile', $thread->creator) }}">
          {{ $thread->creator->name }}
        </a>
        posted: <span v-text="thread.title"></span>
      </span>
    </div>
  </div>

  <div class="panel-body" v-text="thread.body">
  </div>

  <div class="panel-footer" v-if="authorize('owns', thread)">
    <button class="btn btn-xs" @click="editing = true">Edit</button>
  </div>
</div>