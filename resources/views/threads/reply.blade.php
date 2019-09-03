<reply :attributes="{{ $reply }}" inline-template v-cloak>
  <div id="reply-{{ $reply->id }}" class="panel panel-default">
    <div class="panel-heading">
      <div class="level">
        <h5 class="flex">
          <a href="{{ route('profile', $reply->owner) }}">
            {{ $reply->owner->name }}
          </a>
          said {{ $reply->created_at->diffForHumans() }}…
        </h5>

        <div>
          <form action="{{ $reply->resourcePath() . '/favorites' }}" method="post">
            {{ csrf_field() }}

            <button
              type="submit"
              class="btn btn-default"
              {{ $reply->isFavorited() ? 'disabled' : '' }}
            >
              {{ $reply->getFavoritesCountAttribute() }} {{ str_plural('Favorite', $reply->getFavoritesCountAttribute() ) }}
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="panel-body">
      <div v-if="editing">
        <div class="form-group">
          <textarea class="form-control" v-model="body"></textarea>
        </div>

        <button class="btn btn-xs btn-primary" @click.once="update">Update</button>
        <button class="btn btn-xs btn-link" @click="cancel">Cancel</button>
      </div>
      <div v-else v-text="body">
      </div>
    </div>

    @can('update', $reply)
      <div class="panel-footer level">
        <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
        <form action="{{ $reply->resourcePath() }}" method="post">
          {{ csrf_field() }}
          {{ method_field('delete') }}

          <button type="submit" class="btn btn-danger btn-xs">Delete</button>
        </form>
      </div>
    @endcan
  </div>
</reply>
