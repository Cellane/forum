<div class="panel panel-default">
  <div class="panel-heading">
    <div class="level">
      <h5 class="flex">
        <a href="#">
          {{ $reply->owner->name }}
        </a>
        said {{ $reply->created_at->diffForHumans() }}…
      </h5>

      <div>
        <form action="{{ $reply->path() . '/favorites' }}" method="post">
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
    {{ $reply->body }}
  </div>
</div>