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
export default {
  props: ["endpoint"],

  data() {
    return {
      body: ""
    }
  },

  computed: {
    signedIn() {
      return window.App.signedIn
    }
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
