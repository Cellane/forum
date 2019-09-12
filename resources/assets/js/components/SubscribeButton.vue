<template>
  <button :class="classes" @click="subscribe">{{ text }}</button>
</template>

<script>
export default {
  props: ["active"],

  data() {
    return {
      isSubscribed: this.active
    }
  },

  computed: {
    classes() {
      return ["btn", this.isSubscribed ? "btn-danger" : "btn-primary"]
    },

    method() {
      return this.isSubscribed ? "delete" : "post"
    },

    text() {
      return this.isSubscribed ? "Unsubscribe" : "Subscribe"
    }
  },

  methods: {
    subscribe() {
      axios[this.method](`${location.pathname}/subscriptions`).then(() => {
        this.isSubscribed = this.method === "post"
      })
    }
  }
}
</script>

