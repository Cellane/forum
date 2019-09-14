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
            <wysiwyg v-model="body"></wysiwyg>
          </div>
        </form>
      </div>
      <div v-else v-html="body"></div>
    </div>

    <div class="panel-footer" v-if="canUpdate || canMarkBestReply">
      <div class="level">
        <template v-if="canUpdate">
          <template v-if="editing">
            <button class="btn btn-xs btn-primary mr-1">Update</button>
            <button class="btn btn-xs mr-1" type="button" @click="cancel">
              Cancel
            </button>
            <button class="btn btn-danger btn-xs" @click="destroy">
              Destroy
            </button>
          </template>

          <button class="btn btn-xs mr-1" @click="editing = true" v-else>
            Edit
          </button>
        </template>

        <button
          class="btn btn-success btn-xs ml-a"
          @click="markBestReply"
          v-if="canMarkBestReply"
        >
          Best reply?
        </button>
      </div>
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
      isBest: this.data.isBest
    }
  },

  created() {
    window.events.$on("best-reply-selected", id => {
      this.isBest = this.id === id
    })
  },

  computed: {
    ago() {
      return moment(this.data.created_at).fromNow()
    },

    canUpdate() {
      return this.authorize("owns", this.data)
    },

    canMarkBestReply() {
      return this.authorize("owns", this.data.thread) && !this.isBest
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
      axios.post(`/replies/${this.data.id}/best`).then(() => {
        window.events.$emit("best-reply-selected", this.data.id)
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
