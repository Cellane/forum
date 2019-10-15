<template>
  <div>
    <div class="level">
      <img :src="avatar" width="50" height="50" class="mr-1" />

      <h1>
        {{ user.name }}
        <small>{{ user.reputation }} XP</small>
      </h1>
    </div>

    <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
      <image-upload name="avatar" class="mr-1" @loaded="onLoad"></image-upload>
    </form>
  </div>
</template>

<script>
import ImageUpload from "./ImageUpload"

export default {
  props: ["user"],

  data() {
    return {
      avatar: this.user.avatar_path
    }
  },

  computed: {
    canUpdate() {
      return this.authorize(user => user.id === this.user.id)
    }
  },

  methods: {
    onLoad({ src, file }) {
      this.avatar = src
      this.persist(file)
    },

    persist(file) {
      let data = new FormData()

      data.append("avatar", file)

      axios
        .post(`/api/users/${this.user.name}/avatar`, data)
        .then(() => flash("Avatar uploaded!"))
    }
  },

  components: {
    ImageUpload
  }
}
</script>
