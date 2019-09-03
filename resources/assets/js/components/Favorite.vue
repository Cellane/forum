<template>
  <button :class="classes" @click="toggle">
    <span class="glyphicon glyphicon-heart"></span>
    <span>{{ count }}</span>
  </button>
</template>

<script>
export default {
  props: ["reply"],

  data() {
    return {
      count: this.reply.favoritesCount,
      active: this.reply.isFavorited
    }
  },

  computed: {
    classes() {
      return ["btn", this.active ? "btn-danger" : "btn-default"]
    }
  },

  methods: {
    toggle() {
      const method = this.active ? "delete" : "post"

      axios[method](`/replies/${this.reply.id}/favorites`).then(() => {
        if (method === "post") {
          this.active = true
          this.count++
        } else {
          this.active = false
          this.count--
        }
      })
    }
  }
}
</script>
