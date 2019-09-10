<template>
  <div
    :id="`reply-${id}`"
    class="panel"
    :class="isBest ? 'panel-success' : 'panel-default'"
  >
    <div class="panel-heading">
      <div class="level">
        <h5 class="flex">
          <a :href="`/profiles/${data.owner.name}`">
            {{ data.owner.name }}
          </a>
          said {{ ago }}…
        </h5>

        <favorite :reply="data" :disabled="!signedIn"></favorite>
      </div>
    </div>

    <div class="panel-body">
      <div v-if="editing">
        <form @submit.prevent="update">
          <div class="form-group">
            <textarea class="form-control" v-model="body" required></textarea>
          </div>

          <button class="btn btn-xs btn-primary">Update</button>
          <button class="btn btn-xs btn-link" type="button" @click="cancel">
            Cancel
          </button>
        </form>
      </div>
      <div v-else v-html="body"></div>
    </div>

    <div class="panel-footer level" v-if="canUpdate || !isBest">
      <div v-if="authorize('updateReply', reply)">
        <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
        <button class="btn btn-danger btn-xs" @click="destroy">Destroy</button>
      </div>
      <button
        class="btn btn-success btn-xs ml-a"
        @click="markBestReply"
        v-if="!isBest"
      >
        Best reply?
      </button>
    </div>
  </div>
</template>

<script>
import moment from "moment"
import Favorite from "./Favorite"

export default {
  props: ["data"],

  data() {
    return {
      editing: false,
      id: this.data.id,
      body: this.data.body,
      isBest: false,
      reply: this.data
    }
  },

  computed: {
    ago() {
      return `${moment(this.data.created_at).fromNow()}…`
    }
  },

  methods: {
    update() {
      axios
        .patch(`/replies/${this.data.id}`, {
          body: this.body
        })
        .then(() => {
          this.editing = false
          this.data.body = this.body
          flash("Updated!")
        })
        .catch(({ response }) => flash(response.data, "danger"))
    },

    destroy() {
      axios.delete(`/replies/${this.data.id}`).then(() => {
        this.$emit("deleted", this.data.id)
      })
    },

    markBestReply() {
      this.isBest = true
    },

    cancel() {
      this.body = this.data.body
      this.editing = false
    }
  },

  components: {
    Favorite
  }
}
</script>
