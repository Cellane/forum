<template>
  <div v-if="signedIn">
    <div class="form-group">
      <textarea
        name="body"
        id="body"
        rows="5"
        class="form-control"
        placeholder="Have something to say?"
        v-model="body"
      ></textarea>
    </div>

    <button type="submit" class="btn btn-default" @click="addReply">
      Post
    </button>
  </div>
  <div v-else>
    <p class="text-center">
      Please <a href="/login">sign in</a> to participate in this discussion.
    </p>
  </div>
</template>

<script>
import "at.js"
import "jquery.caret"

export default {
  props: ["endpoint"],

  data() {
    return {
      body: ""
    }
  },

  mounted() {
    $("#body").atwho({
      at: "@",
      delay: 500,
      callbacks: {
        remoteFilter: (name, callback) => {
          if (name == null || name.length < 2) {
            return callback([])
          }

          $.getJSON("/api/users/", { name }, usernames => callback(usernames))
        }
      }
    })
  },

  methods: {
    addReply() {
      axios
        .post(this.endpoint, { body: this.body })
        .then(({ data }) => {
          this.body = ""
          this.$emit("created", data)
          flash("Your reply has been posted.")
        })
        .catch(({ response }) => flash(response.data, "danger"))
    }
  }
}
</script>
