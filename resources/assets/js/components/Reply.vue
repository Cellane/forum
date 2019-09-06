<template>
  <div :id="`reply-${id}`" class="panel panel-default">
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
        <div class="form-group">
          <textarea class="form-control" v-model="body"></textarea>
        </div>

        <button class="btn btn-xs btn-primary" @click.once="update">
          Update
        </button>
        <button class="btn btn-xs btn-link" @click="cancel">Cancel</button>
      </div>
      <div v-else v-text="body"></div>
    </div>

    <div class="panel-footer level" v-if="canUpdate">
      <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
      <button class="btn btn-danger btn-xs" @click="destroy">Destroy</button>
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
      body: this.data.body
    }
  },

  computed: {
    signedIn() {
      return window.App.signedIn
    },

    canUpdate() {
      return this.authorize(user => this.data.user_id == user.id)
    },

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
    },

    destroy() {
      axios.delete(`/replies/${this.data.id}`).then(() => {
        this.$emit("deleted", this.data.id)
      })
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
